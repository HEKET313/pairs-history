<?php

namespace App\Controller;

use App\Entity\Pair;
use App\Entity\PairData;
use App\Exception\NotFoundException;
use App\Request\GetDataRequest;
use App\Service\PairService;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilterController
 * @package App\Controller
 * @Route("/api/pair")
 */
class PairsController extends AbstractController
{
    private PairService $service;

    public function __construct(PairService $service)
    {
        $this->service = $service;
    }

    /**
     * @SWG\Get(
     *     summary="Returns list of all available pairs",
     *     tags={"Pair"},
     *     @SWG\Response(
     *         response="200",
     *         description="Pairs retrieved",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="string")
     *         )
     *     )
     * )
     * @return Response
     * @Route("", methods={"GET"})
     */
    public function getList(): Response
    {
        $pairs = $this->service->getAvailablePairs();
        return $this->json(array_map(fn(Pair $pair) => $pair->getName(), $pairs));
    }

    /**
     * @SWG\Get(
     *     summary="Returns data for particular pair",
     *     tags={"Pair"},
     *     @SWG\Parameter(in="path", name="pair", type="string", description="Pair code"),
     *     @SWG\Parameter(in="query", name="date_from", type="string", description="Date from"),
     *     @SWG\Parameter(in="query", name="date_to", type="string", description="Date to"),
     *     @SWG\Response(
     *         response="200",
     *         description="Pair data retrieved",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(type="object",
     *                 @SWG\Property(property="date_time", type="string"),
     *                 @SWG\Property(property="price", type="number")
     *             )
     *         )
     *     )
     * )
     * @param GetDataRequest $request
     * @return Response
     * @Route("/{pair}", methods={"GET"})
     * @throws NotFoundException
     */
    public function getPairData(GetDataRequest $request): Response
    {
        $pair = $this->service->getPairData($request);
        if (!$pair) {
            return $this->json([]);
        }
        return $this->json(array_map(fn(PairData $data) => [
            'date_time' => $data->getDateTime()->format('Y-m-d H:i:s'),
            'price' => $data->getPrice(),
        ], $pair->getData()->toArray()));
    }
}
