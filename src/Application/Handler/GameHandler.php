<?php

namespace App\Application\Handler;

use App\Application\ErrorHandler\ErrorHandler;
use App\Domain\Game\Game;
use App\Infrastructure\Repository\GameRepository;
use App\Infrastructure\Repository\TeamRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameHandler
{
    private GameRepository $gameRepository;

    private TeamRepository $teamRepository;

    private ErrorHandler $errorHandler;

    public function __construct(
        GameRepository $gameRepository,
        TeamRepository $teamRepository,
        ErrorHandler $errorHandler
    )
    {
        $this->gameRepository = $gameRepository;
        $this->teamRepository = $teamRepository;
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

        $homeTeam = $this->teamRepository->findOneBy(['id' => $gameArray['homeTeamId']]);
        $awayTeam = $this->teamRepository->findOneBy(['id' => $gameArray['awayTeamId']]);

        $game->setHomeTeam($homeTeam);
        $game->setAwayTeam($awayTeam);
        $game->setGameTime(new DateTimeImmutable($gameArray['gameTime']));
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
            'gameTime' => $game->getGameTime()->format('H:i:s, d M Y'),
            'score' => $game->getScore(),
        ];
    }

    /**
     * @param int $id
     * @param array $gameArray
     * @return array
     * @throws Exception
     */
    public function handlerUpdateGame(int $id, array $gameArray): array
    {
        $currentGame = $this->gameRepository->findOneBy(['id' => $id]);

        $homeTeam = $this->teamRepository->findOneBy(['id' => $gameArray['homeTeamId']]);
        $awayTeam = $this->teamRepository->findOneBy(['id' => $gameArray['awayTeamId']]);

        $currentGame->setHomeTeam($homeTeam);
        $currentGame->setAwayTeam($awayTeam);
        $currentGame->setGameTime(New DateTimeImmutable($gameArray['gameTime']));
        $currentGame->setScore($gameArray['score']);

        $errors = $this->errorHandler->validate($currentGame);

        if ($errors) return $errors;

        try {
            $this->gameRepository->save($currentGame);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'homeTeam' => $currentGame->getHomeTeam()->getName(),
            'awayTeam' => $currentGame->getAwayTeam()->getName(),
            'gameTime' => $currentGame->getGameTime()->format('H:i:s, d M Y'),
            'score' => $currentGame->getScore()
        ];
    }
}