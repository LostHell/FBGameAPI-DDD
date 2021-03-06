<?php

namespace App\Controller\Player;

use App\Application\Handler\PlayerHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GetPlayerController
 * @package App\Controller\Player
 */
class GetPlayerController extends AbstractController
{
    private PlayerHandler $handler;

    public function __construct(PlayerHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function getAllPlayers(): Response
    {
        try {
            $players = $this->handler->handlerGetAllPlayers();
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($players->toArray(),
            Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getById(int $id): Response
    {
        try {
            $player = $this->handler->handlerGetById($id);
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($player,
            Response::HTTP_OK);
    }
}