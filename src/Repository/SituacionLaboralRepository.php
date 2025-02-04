<?php

namespace App\Repository;

use App\Entity\SituacionLaboral;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method SituacionLaboral|null find($id, $lockMode = null, $lockVersion = null)
 * @method SituacionLaboral|null findOneBy(array $criteria, array $orderBy = null)
 * @method SituacionLaboral[]    findAll()
 * @method SituacionLaboral[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SituacionLaboralRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SituacionLaboral::class);
    }
}
