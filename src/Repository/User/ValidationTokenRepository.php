<?php

namespace App\Repository\User;

use App\Entity\User\User;
use App\Entity\User\ValidationToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ValidationToken>
 */
class ValidationTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValidationToken::class);
    }

    public function save(ValidationToken $entity, bool $flush = false)
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function depreciateUserTokens(User $user)
    {
        $this->createQueryBuilder('t')
            ->update()
            ->set('t.status', ':status')
            ->setParameter('status', ValidationToken::STATUS_DEPRECATED)
            ->andWhere('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute()
        ;
    }
}
