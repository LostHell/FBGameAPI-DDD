<?php

namespace App\Application\Handler;

use App\Domain\Team\Team;
use App\Infrastructure\Repository\TeamRepository;
use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TeamHandler
{
    private TeamRepository $teamRepository;
    private ValidatorInterface $validator;

    public function __construct(
        TeamRepository $teamRepository,
        ValidatorInterface $validator)
    {
        $this->teamRepository = $teamRepository;
        $this->validator = $validator;
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
     * @return array|ConstraintViolationListInterface
     * @throws Exception
     */
    public function handlerCreateTeam(array $teamArray)
    {
        $team = new Team();
        $team->setName($teamArray['name']);
        $team->setLogo($teamArray['logo']);

        $errors = $this->validator->validate($team);

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
}