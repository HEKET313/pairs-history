<?php

namespace App\ArgumentResolver;

use App\System\Request\IRequest;
use App\System\Request\IValidate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestArgumentResolver implements ArgumentValueResolverInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @inheritdoc
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        $type = $argument->getType();
        return class_exists($type) && isset(class_implements($type)[IRequest::class]);
    }

    /**
     * @inheritdoc
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $bodyParams = [];
        $body = json_decode($request->getContent(), true);
        if (is_array($body) && !json_last_error()) {
            $bodyParams = $body;
        }

        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter(), null, new ReflectionExtractor());
        $serializer = new Serializer([new DateTimeNormalizer(), $normalizer]);

        $params = array_merge(
            iterator_to_array($request->attributes->getIterator())['_route_params'] ?? [],
            iterator_to_array($request->query->getIterator()),
            iterator_to_array($request->request->getIterator()),
            $bodyParams
        );

        $type = $argument->getType();

        $r = $serializer->denormalize($params, $type, null, [
            ObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true
        ]);
        $errors = $this->validator->validate($r);
        if (count($errors) !== 0) {
            $e = $errors->get(0);
            throw new \Exception($e->getMessage(), 'VALIDATION_ERROR', [
                'property_path' => $e->getPropertyPath(),
                'invalid_value' => $e->getInvalidValue()
            ]);
        }
        if ($r instanceof IValidate) {
            $r->validate();
        }
        yield $r;
    }
}
