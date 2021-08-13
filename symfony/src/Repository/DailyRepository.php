<?php

namespace App\Repository;

use App\Entity\Daily;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Daily|null find($id, $lockMode = null, $lockVersion = null)
 * @method Daily|null findOneBy(array $criteria, array $orderBy = null)
 * @method Daily[]    findAll()
 * @method Daily[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Daily::class);
    }

}
