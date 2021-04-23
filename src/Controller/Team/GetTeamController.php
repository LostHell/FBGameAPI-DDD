<?php

namespace App\Controller\Team;

use App\Application\Handler\TeamHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GetTeamController extends AbstractController
{
    private TeamHandler $handler;

    public function __construct(TeamHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function getAllTeams(): Response
    {
        try {
            $teams = $this->handler->handlerGetAllTeams();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return new JsonResponse($teams->toArray());
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getTeamById(int $id): Response
    {
        try {
            $team = $this->handler->handlerGetById($id);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return new JsonResponse($team);
    }
}