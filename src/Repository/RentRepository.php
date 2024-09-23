<?php

namespace App\Repository;

use App\Entity\Rent;
use App\Entity\User; // Importer la classe User
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rent>
 */
class RentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rent::class);
    }

    /**
     * @return Rent[] Returns an array of Rent objects for a given user
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}  