<?php

namespace App\Service;

use App\Repository\PairDataRepository;
use App\Repository\PairRepository;
use App\System\Binance\Client;
use Psr\Log\LoggerInterface;

class ImportService
{
    private Client $client;
    private PairRepository $repository;
    private LoggerInterface $logger;
    private PairDataRepository $pairDataRepository;

    public function __construct(
        Client $client,
        PairRepository $repository,
        PairDataRepository $pairDataRepository,
        LoggerInterface $logger
    )
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->logger = $logger;
        $this->pairDataRepository = $pairDataRepository;
    }

    public function importNewData(): void
    {
        foreach ($this->repository->getAllWithLastUpdate() as $pair) {
            try {
                $rows = [];
                $i = 0;
                foreach ($this->getHourlyData($pair['name'], $pair['last_update']) as $dateTime => $price) {
                    $rows[] = [
                        'date_time' => $dateTime,
                        'price' => $price,
                    ];
                    $i++;
                    if ($i === 100) {
                        $this->pairDataRepository->importData($pair['id'], $rows);
                        $rows = [];
                        $i = 0;
                    }
                }
                $this->pairDataRepository->importData($pair['id'], $rows);
            } catch (\Throwable $ex) {
                $this->logger->error('Can not save pair data to DB: {message}', [
                    'message' => $ex->getMessage(),
                    'pair_id' => $pair['id']
                ]);
            }
        }
    }

    private function getHourlyData(string $pairName, ?string $lastUpdate): iterable
    {
        $dateFrom = $lastUpdate ? new \DateTime($lastUpdate) : new \DateTime('-5 days');
        try {
            $result = $this->client->getHistoryHourly($dateFrom, new \DateTime('now'), $pairName);
            if (!$result) {
                $this->logger->error('Can not get data for "{pair}" from Binance', [
                    'pair' => $pairName,
                    'date_from' => $dateFrom->format('Y-m-d H:i:s')
                ]);
                return;
            }
        } catch (\Throwable $exception) {
            $this->logger->error('Can not get data for "{pair}" from Binance', [
                'pair' => $pairName,
                'date_from' => $dateFrom->format('Y-m-d H:i:s')
            ]);
            return;
        }
        foreach ($result as $row) {
            $dateTime = new \DateTime();
            $dateTime->setTimestamp($row['openTime'] / 1000);
            yield $dateTime => (float)$row['close'];
        }
    }
}
