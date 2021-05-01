<?php

namespace App\Controller\Game;

use App\Application\Handler\GameHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreateGameController
 * @package App\Controller\Game
 */
class CreateGameController extends AbstractController
{
    private GameHandler $handler;

    public function __construct(GameHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $game = json_decode($request->getContent(), true);

        try {
            $newGame = $this->handler->handlerCreateGame(
                [
                    'homeTeam' => $game['homeTeam'],
                    'awayTeam' => $game['awayTeam'],
                    'gameTime' => $game['gameTime'],
                    'score' => $game['score'],
                ]
            );
        } catch (Exception $ex) {
            return new JsonResponse($ex->getMessage(),
                Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($newGame,
            Response::HTTP_CREATED);
    }
}