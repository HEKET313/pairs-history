<?php

namespace App\EventListener;

use App\Exception\ProcessedErrorException;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ExceptionListener
 * @package App\EventListener
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ExceptionListener implements EventSubscriberInterface
{
    private KernelInterface $kernel;
    private LoggerInterface $logger;

    /**
     * ExceptionListener constructor.
     * @param KernelInterface $kernel
     * @param LoggerInterface $logger
     */
    public function __construct(KernelInterface $kernel, LoggerInterface $logger)
    {
        $this->kernel = $kernel;
        $this->logger = $logger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $e = $event->getThrowable();

        if ($e instanceof ProcessedErrorException) {
            $data = [
                'code' => $e->getErrorCode(),
                'message' => $e->getMessage()
            ];
            if ($e->getData()) {
                $data['data'] = $e->getData();
            }
            $event->setResponse(new JsonResponse($data, $e->getCode()));
        } else {
            $this->logger->error('Unhandled error: ' . $e->getMessage(), ['stacktrace' => $e->getTraceAsString()]);
            $event->setResponse(new JsonResponse([
                'message' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR));
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['onKernelException', 10]]
        ];
    }
}
