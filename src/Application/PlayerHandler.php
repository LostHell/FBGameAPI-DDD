<?php

namespace App\Application;

use App\Domain\Player\Player;
use App\Infrastructure\Repository\PlayerRepository;
use DateTimeImmutable;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerHandler
{
    /**
     * @var PlayerRepository
     */
    private PlayerRepository $playerRepository;

    /**
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(
        PlayerRepository $playerRepository,
        ValidatorInterface $validator,
        UserPasswordEncoderInterface $encoder
    )
    {
        $this->playerRepository = $playerRepository;
        $this->validator = $validator;
        $this->encoder = $encoder;
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function handleGetById(int $id): array
    {
        try {
            $player = $this->playerRepository->findOneBy(['id' => $id]);
        } catch (Exception $ex) {
            throw new Exception("Player with $id is not exists");
        }

        return [
            'username' => $player->getUsername(),
            'email' => $player->getEmail(),
            'point' => $player->getPoint(),
            'isActive' => $player->getIsActive(),
            'avatar' => $player->getAvatar(),
            'createdAt' => $player->getCreatedAt()
        ];
    }

    /**
     * @param array $playerArray
     * @return ConstraintViolationListInterface|null
     * @throws Exception
     */
    public function handleRegister(array $playerArray): ?ConstraintViolationListInterface
    {
        $player = new Player();
        $player->setUsername($playerArray['username']);
        $player->setEmail($playerArray['email']);
        $player->setPoint(0);
        $player->setIsActive(true);
        $player->setAvatar($playerArray['avatar']);
        $player->setCreatedAt(new DateTimeImmutable());
        $player->setPassword(
            $this->encoder->encodePassword($player, $playerArray['password'])
        );

        $errors = $this->validator->validate($player);

        if (count($errors) > 0) {
            return $errors;
        }

        try {
            $this->playerRepository->save($player);
        } catch (Exception $ex) {
            throw new Exception("This ${playerArray['username']} Player is invalid! Please try again!");
        }

        return null;
    }
}