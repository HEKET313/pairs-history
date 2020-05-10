<?php

namespace App\Repository;

use App\Entity\Pair;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

class PairRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pair::class);
    }

    public function findByNameWithData(string $name, ?\DateTimeInterface $dateFrom, ?\DateTimeInterface $dateTo): ?Pair
    {
        if (!$dateFrom) {
            $dateFrom = new \DateTime('-5 days');
        }
        $qb = $this->createQueryBuilder('p')
            ->select('p', 'pd')
            ->leftJoin('p.data', 'pd')
            ->where('p.name = :name')->setParameter('name', $name)
            ->andWhere('pd.dateTime >= :dateFrom')->setParameter('dateFrom', $dateFrom, Types::DATETIME_MUTABLE)
            ->orderBy('pd.dateTime');
        if ($dateTo) {
            $qb->andWhere('pd.dateTime <= :dateTo')->setParameter('dateTo', $dateTo, Types::DATETIME_MUTABLE);
        }
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getAllWithLastUpdate(): iterable
    {
        $result = $this->getEntityManager()->getConnection()
            ->executeQuery('
                SELECT p.id, p.name, max(pd.date_time) last_update
                FROM pair p LEFT JOIN pair_data pd on p.id = pd.pair_id 
                GROUP BY p.name, p.id
            ');
        while ($row = $result->fetch()) {
            yield $row;
        }
    }
}
