<?php

namespace App\Application\Handler;

use App\Domain\Player\Player;
use App\Infrastructure\Repository\PlayerRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlayerHandler
{
    private PlayerRepository $playerRepository;

    private ValidatorInterface $validator;

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
                    'createdAt' => $player->getCreatedAt()
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
            'createdAt' => $player->getCreatedAt()
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

        $errors = $this->validator->validate($player);

        if (count($errors) > 0) {
            $errorsArray = [];

            for ($i = 0; $i < $errors->count(); $i++) {
                $errorsArray[$i] = [
                    'message' => $errors->get($i)->getMessage(),
                    'parameters' => "Invalid parameter:" . ' ' . $errors->get($i)->getPropertyPath(),
                    'value' => "Invalid value:" . ' ' . $errors->get($i)->getInvalidValue()
                ];
            }

            return $errorsArray;
        }

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
     * @param int $id
     * @param array $playerData
     * @return array
     * @throws Exception
     */
    public function handlerUpdate(int $id, array $playerData): array
    {
        $currentPlayer = $this->playerRepository->findOneBy(['id' => $id]);
        $currentPlayer->setUsername($playerData['username']);
        $currentPlayer->setEmail($playerData['email']);
        $currentPlayer->setAvatar($playerData['avatar']);
        $currentPlayer->setPassword($playerData['password']);

        try {
            $this->playerRepository->save($currentPlayer);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'username' => $player['username'],
            'email' => $player['email'],
            'avatar' => $player['avatar'],
            'password' => $player['password']
        ];
    }
}