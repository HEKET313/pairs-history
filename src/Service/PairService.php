<?php

namespace App\Service;

use App\Entity\Pair;
use App\Exception\NotFoundException;
use App\Repository\PairRepository;
use App\Request\GetDataRequest;

class PairService
{
    private PairRepository $repository;

    public function __construct(PairRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAvailablePairs()
    {
        return $this->repository->findAll();
    }

    /**
     * @param GetDataRequest $request
     * @return Pair
     * @throws NotFoundException
     */
    public function getPairData(GetDataRequest $request): ?Pair
    {
        if (!$this->repository->findOneBy(['name' => $request->getPair()])) {
            throw new NotFoundException('Pair not found');
        }
        return $this->repository->findByNameWithData(
            $request->getPair(),
            $request->getDateFrom(),
            $request->getDateTo()
        );
    }
}
