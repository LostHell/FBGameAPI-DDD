<?php

namespace App\Application\Handler;

use App\Application\ErrorHandler\ErrorHandler;
use App\Domain\Team\Team;
use App\Infrastructure\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class TeamHandler
{
    private TeamRepository $teamRepository;

    private ErrorHandler $errorHandler;

    public function __construct(
        TeamRepository $teamRepository,
        ErrorHandler $errorHandler)
    {
        $this->teamRepository = $teamRepository;
        $this->errorHandler = $errorHandler;
    }

    /**
     * @return ArrayCollection
     * @throws Exception
     */
    public function handlerGetAllTeams(): ArrayCollection
    {
        try {
            $teams = $this->teamRepository->findAll();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        $data = new ArrayCollection();

        foreach ($teams as $team) {
            $data->add(
                [
                    'name' => $team->getName(),
                    'logo' => $team->getLogo()
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
            $team = $this->teamRepository->findOneBy(['id' => $id]);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'name' => $team->getName(),
            'logo' => $team->getLogo()
        ];
    }

    /**
     * @param array $teamArray
     * @return array
     * @throws Exception
     */
    public function handlerCreateTeam(array $teamArray): array
    {
        $team = new Team();

        $team->setName($teamArray['name']);
        $team->setLogo($teamArray['logo']);

        $errors = $this->errorHandler->validate($team);

        if ($errors) {
            return $errors;
        }

        try {
            $this->teamRepository->save($team);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'name' => $team->getName(),
            'logo' => $team->getLogo()
        ];
    }

    /**
     * @param int $id
     * @param array $teamData
     * @return array
     * @throws Exception
     */
    public function handlerUpdateTeam(int $id, array $teamData): array
    {
        $currentTeam = $this->teamRepository->findOneBy(['id' => $id]);

        $currentTeam->setName($teamData['name']);
        $currentTeam->setLogo($teamData['logo']);

        $errors = $this->errorHandler->validate($currentTeam);

        if ($errors) return $errors;

        try {
            $this->teamRepository->save($currentTeam);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'name' => $currentTeam->getName(),
            'logo' => $currentTeam->getLogo()
        ];
    }

    /**
     * @param int $id
     * @return array
     * @throws Exception
     */
    public function handlerDeleteTeam(int $id): array
    {
        $currentTeam = $this->teamRepository->findOneBy(['id' => $id]);

        try {
            $this->teamRepository->remove($currentTeam);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return [
            'name' => $currentTeam->getName(),
            'logo' => $currentTeam->getLogo()
        ];
    }
}