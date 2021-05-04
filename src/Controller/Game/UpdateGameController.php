<?php

namespace App\Controller\Game;

use App\Application\Handler\GameHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateGameController extends AbstractController
{
    private GameHandler $handler;

    public function __construct(GameHandler $handler)
    {
        $this->handler = $handler;
    }

    public function update(int $id, Request $request): Response
    {
        $game = json_decode($request->getContent(), true);

        try {
            $currentGame = $this->handler->handlerUpdateGame($id,
                [
                    'homeTeamId' => $game['homeTeamId'],
                    'awayTeamId' => $game['awayTeamId'],
                    'gameTime' => $game['gameTime'],
                    'score' => $game['score']
                ]
            );
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_MODIFIED);
        }

        return new JsonResponse($currentGame,
            Response::HTTP_OK);
    }
}