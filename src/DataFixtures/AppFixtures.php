<?php

namespace App\DataFixtures;

use App\Domain\Player\Player;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadPlayer($manager);
    }

    public function loadPlayer(ObjectManager $manager)
    {
        $player = new Player();
        $player->setUsername('john.doe');
        $player->setEmail('john.doe@gmail.com');
        $player->setPoint(0);
        $player->setIsActive(true);
        $player->setAvatar('www.google.bg');
        $player->setCreatedAt(new DateTimeImmutable());

        $encoded = $this->encoder->encodePassword($player, 'password');
        $player->setPassword($encoded);

        $manager->persist($player);
        $manager->flush();
    }
}
