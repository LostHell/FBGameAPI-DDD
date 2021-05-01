<?php

namespace App\Controller\Team;

use App\Application\Handler\TeamHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateTeamController extends AbstractController
{
    private TeamHandler $handler;

    public function __construct(TeamHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function update(int $id, Request $request): Response
    {
        $teamArray = json_decode($request->getContent(), true);

        try {
            $teamData = $this->handler->handlerUpdateTeam($id, [
                'name' => $teamArray['name'],
                'logo' => $teamArray['logo']
            ]);
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_MODIFIED);
        }

        return new JsonResponse($teamData,
            Response::HTTP_OK);
    }
}