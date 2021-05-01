<?php

namespace App\Application\Handler;

use App\Application\ErrorHandler\ErrorHandler;
use App\Domain\Game\Game;
use App\Infrastructure\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameHandler
{
    private GameRepository $gameRepository;

    private ErrorHandler $errorHandler;

    public function __construct(
        GameRepository $gameRepository,
        ErrorHandler $errorHandler
    )
    {
        $this->gameRepository = $gameRepository;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @return ArrayCollection
     * @throws Exception
     */
    public function handlerGetAllGames(): ArrayCollection
    {
        try {
            $games = $this->gameRepository->findAll();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        if (empty($games)) throw new NotFoundHttpException('We don\'t have any games!');

        $data = new ArrayCollection();

        foreach ($games as $game) {
            $data->add(
                [
                    'homeTeam' => $game->getHomeTeam()->getName(),
                    'awayTeam' => $game->getAwayTeam()->getName(),
                    'gameTime' => $game->getGameTime()->format('H:i:s, d M Y'),
                    'score' => $game->getScore(),
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
    public function handlerGetGameById(int $id): array
    {
        try {
            $game = $this->gameRepository->findOneBy(['id' => $id]);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        if (empty($game)) throw new NotFoundHttpException("Game with id:$id is not found!");

        return [
            'homeTeam' => $game->getHomeTeam()->getName(),
            'awayTeam' => $game->getAwayTeam()->getName(),
            'gameTime' => $game->getGameTime()->format('H:i:s, d M Y'),
            'score' => $game->getScore(),
        ];
    }

    /**
     * @param array $gameArray
     * @return array
     * @throws Exception
     */
    public function handlerCreateGame(array $gameArray): array
    {
        $game = new Game();

        $game->setHomeTeam($gameArray['homeTeam']);
        $game->setAwayTeam($gameArray['awayTeam']);
        $game->setGameTime($gameArray['gameTime']);
        $game->setScore($gameArray['score']);

        $errors = $this->errorHandler->validate($game);

        if ($errors) return $errors;

        try {
            $this->gameRepository->save($game);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'homeTeam' => $game->getHomeTeam()->getName(),
            'awayTeam' => $game->getAwayTeam()->getName(),
            'gameTime' => $game->getGameTime(),
            'score' => $game->getScore(),
        ];
    }
}