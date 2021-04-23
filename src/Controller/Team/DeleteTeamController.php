<?php

namespace App\Controller\Team;

use App\Application\Handler\TeamHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class DeleteTeamController extends AbstractController
{
    private TeamHandler $handler;

    public function __construct(TeamHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function delete(int $id): Response
    {
        try {
            $team = $this->handler->handlerDeleteTeam($id);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return new JsonResponse($team);
    }
}