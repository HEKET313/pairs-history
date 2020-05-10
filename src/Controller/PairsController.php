<?php

namespace App\Controller;

use App\Entity\Pair;
use App\Entity\PairData;
use App\Exception\NotFoundException;
use App\Request\GetDataRequest;
use App\Service\ImportService;
use App\Service\PairService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilterController
 * @package App\Controller
 */
class PairsController extends AbstractController
{
    private PairService $service;

    public function __construct(PairService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Response
     * @Route("", methods={"GET"})
     */
    public function getList(): Response
    {
        $pairs = $this->service->getAvailablePairs();
        return $this->json(array_map(fn(Pair $pair) => $pair->getName(), $pairs));
    }

    /**
     * @param GetDataRequest $request
     * @return Response
     * @Route("/{pair}", methods={"GET"})
     */
    public function getPairData(GetDataRequest $request): Response
    {
        $pair = $this->service->getPairData($request);
        if (!$pair) {
            return $this->json([]);
        }
        return $this->json(array_map(fn(PairData $data) => [
            'dateTime' => $data->getDateTime()->format('Y-m-d H:i:s'),
            'price' => $data->getPrice(),
        ], $pair->getData()->toArray()));
    }
}
