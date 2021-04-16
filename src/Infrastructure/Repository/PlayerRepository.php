<?php

namespace App\Infrastructure\Repository;

use App\Domain\Player\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    /**
     * @param Player $player
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Player $player)
    {
        $this->_em->persist($player);
        $this->_em->flush();
    }
}
