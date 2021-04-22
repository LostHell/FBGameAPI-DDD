<?php

namespace App\Infrastructure\Repository;

use App\Domain\Team\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
     * @param Team $team
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Team $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }
}
