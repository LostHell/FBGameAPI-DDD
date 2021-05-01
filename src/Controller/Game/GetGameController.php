<?php

namespace App\Controller\Game;

use App\Application\Handler\GameHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GetGameController
 * @package App\Controller\Game
 */
class GetGameController extends AbstractController
{
    private GameHandler $handler;

    public function __construct(GameHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function getAllGames(): Response
    {
        try {
            $games = $this->handler->handlerGetAllGames();
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($games->toArray(),
            Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getGameById(int $id): Response
    {
        try {
            $game = $this->handler->handlerGetGameById($id);
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($game,
            Response::HTTP_OK);
    }
}