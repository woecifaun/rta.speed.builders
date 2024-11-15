<?php

namespace App\Repository\Speedbuilding;

use App\Entity\Speedbuilding\Category;
use App\Entity\Speedbuilding\CategoryVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategoryVersion>
 */
class CategoryVersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryVersion::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(CategoryVersion $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return CategoryVersion[] Returns an array of CategoryVersion objects
     */
    public function getLastEntry(Category $category): CategoryVersion
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.category = :category')
            ->setParameter('category', $category)
            ->orderBy('v.id', 'Desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    public function deleteDraft(Category $category)
    {
        $this->createQueryBuilder('v')
            ->delete()
            ->andWhere('v.category = :category')
            ->setParameter('category', $category)
            ->andWhere('v.status = :status')
            ->setParameter('status', CategoryVersion::STATUS_DRAFT)
            ->getQuery()
            ->execute()
        ;
    }
//    public function findOneBySomeField($value): ?CategoryVersion
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
