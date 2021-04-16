<?php

namespace App\Controller\Player;

use App\Application\PlayerHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdatePlayerController extends AbstractController
{
    private PlayerHandler $handler;

    public function __construct(PlayerHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function update(int $id, Request $request): Response
    {
        $playerArray = json_decode($request->getContent(), true);

        try {
            $player = $this->handler->handleGetById($id);


            $userData = array_merge($player, [
                'username' => $playerArray['username'],
                'email' => $playerArray['email'],
                'avatar' => $playerArray['avatar'],
                'password' => $playerArray['password'],
            ]);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return new JsonResponse($userData);
    }
}