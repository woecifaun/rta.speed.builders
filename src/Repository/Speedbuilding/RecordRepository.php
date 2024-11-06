<?php

namespace App\Repository\Speedbuilding;

use App\Entity\Speedbuilding\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Record>
 */
class RecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function save(Record $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Record[] Returns an array of Record objects
     */
    public function findRecordsPostedMostRecently(int $limit = 10): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.postDate', 'DESC')
            // ->lim
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Record[] Returns an array of Record objects
     */
    public function getRank(Record $record): int
    {
        $qb = $this->createQueryBuilder('r');

        $qb->select($qb->expr()->count('r.id'))
            ->andWhere('r.time < :time')
            ->setParameter('time', $record->getTime())
            ->andWhere('r.category = :category')
            ->setParameter('category', $record->getCategory())
        ;

        return 1 + $qb
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function propagateRank(Record $record)
    {
        $records = $this->createQueryBuilder('r')
            ->andWhere('r.time > :time')
            ->setParameter('time', $record->getTime())
            ->andWhere('r.category = :category')
            ->setParameter('category', $record->getCategory())
            ->getQuery()
            ->getResult()
        ;

        foreach ($records as $loopRecord) {
            $loopRecord->incrementRank();
            $this->getEntityManager()->persist($loopRecord);
        }

        $this->getEntityManager()->flush();
    }
}
