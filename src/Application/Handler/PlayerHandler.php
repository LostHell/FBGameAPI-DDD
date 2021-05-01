<?php

namespace App\Application\Handler;

use App\Application\ErrorHandler\ErrorHandler;
use App\Domain\Player\Player;
use App\Infrastructure\Repository\PlayerRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PlayerHandler
{
    private PlayerRepository $playerRepository;

    private UserPasswordEncoderInterface $encoder;

    private TokenStorageInterface $tokenStorage;

    private ErrorHandler $errorHandler;

    public function __construct(
        PlayerRepository $playerRepository,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage,
        ErrorHandler $errorHandler
    )
    {
        $this->playerRepository = $playerRepository;
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @return ArrayCollection
     * @throws Exception
     */
    public function handlerGetAllPlayers(): ArrayCollection
    {
        try {
            $players = $this->playerRepository->findAll();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        $data = new ArrayCollection();

        foreach ($players as $player) {
            $data->add(
                [
                    'username' => $player->getUsername(),
                    'email' => $player->getEmail(),
                    'point' => $player->getPoint(),
                    'isActive' => $player->getIsActive(),
                    'avatar' => $player->getAvatar(),
                    'createdAt' => $player->getCreatedAt()->format('d-M-Y')
                ]
            );
        }

        return $data;
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function handlerGetById(int $id): array
    {
        try {
            $player = $this->playerRepository->findOneBy(['id' => $id]);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'username' => $player->getUsername(),
            'email' => $player->getEmail(),
            'point' => $player->getPoint(),
            'isActive' => $player->getIsActive(),
            'avatar' => $player->getAvatar(),
            'createdAt' => $player->getCreatedAt()->format('d-M-Y')
        ];
    }

    /**
     * @param array $playerArray
     * @return array
     * @throws Exception
     */
    public function handlerRegister(array $playerArray): array
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

        $errors = $this->errorHandler->validate($player);

        if ($errors) return $errors;

        try {
            $this->playerRepository->save($player);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'username' => $player->getUsername(),
            'email' => $player->getEmail(),
            'point' => $player->getPoint(),
            'avatar' => $player->getAvatar(),
            'createdAt' => $player->getCreatedAt()
        ];
    }

    /**
     * @param array $playerData
     * @return array
     * @throws Exception
     */
    public function handlerUpdate(array $playerData): array
    {
        $currentPlayer = $this->playerRepository->findOneBy(
            ['username' => $this->tokenStorage->getToken()->getUsername()]
        );

        $currentPlayer->setUsername($playerData['username']);
        $currentPlayer->setEmail($playerData['email']);
        $currentPlayer->setAvatar($playerData['avatar']);

        $errors = $this->errorHandler->validate($currentPlayer);

        if ($errors) return $errors;

        try {
            $this->playerRepository->save($currentPlayer);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'username' => $currentPlayer->getUsername(),
            'email' => $currentPlayer->getEmail(),
            'avatar' => $currentPlayer->getAvatar()
        ];
    }
}