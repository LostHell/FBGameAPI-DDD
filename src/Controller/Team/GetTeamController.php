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
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($teams->toArray(),
            Response::HTTP_OK);
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
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($team,
            Response::HTTP_OK);
    }
}