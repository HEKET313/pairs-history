<?php

namespace App\Repository;

use App\Entity\PairData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;
use Safe\Type;

class PairDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PairData::class);
    }

    public function importData(int $pairId, array $data)
    {
        if (!$data) {
            return;
        }
        $conn = $this->getEntityManager()->getConnection();
        $rows = [];
        $params = [];
        $types = [];
        foreach ($data as $row) {
            $rows[] = '(?,?,?)';
            $params[] = $row['date_time'];
            $params[] = $row['price'];
            $params[] = $pairId;
            $types[] = Types::DATETIME_MUTABLE;
            $types[] = Types::FLOAT;
            $types[] = Types::INTEGER;

        }
        $query = sprintf('INSERT INTO pair_data (date_time, price, pair_id) VALUES %s', join(',', $rows));
        $conn->executeQuery($query, $params, $types);
    }
}
