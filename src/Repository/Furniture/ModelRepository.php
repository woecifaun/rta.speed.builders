<?php

namespace App\Repository\Furniture;

use App\Entity\Furniture\Brand;
use App\Entity\Furniture\Model;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Model>
 */
class ModelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Model::class);
    }

        /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Model $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByBrand(Brand $brand)
    {
        return $this->createQueryBuilder('m')
            ->where('m.brand = :brand')
            ->setParameter('brand', $brand)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Brand Returns a single Brand object with the id or null if not exists
     */
    public function findOneById(int $id): ?Model
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    //    /**
    //     * @return Model[] Returns an array of Model objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Model
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
