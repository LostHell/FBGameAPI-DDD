<?php

namespace App\Controller\Team;

use App\Application\Handler\TeamHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CreateTeamController
 * @package App\Controller\Team
 */
class CreateTeamController extends AbstractController
{
    private TeamHandler $handler;

    public function __construct(TeamHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function create(Request $request): Response
    {
        $team = json_decode($request->getContent(), true);

        try {
            $newTeam = $this->handler->handlerCreateTeam(
                [
                    'name' => $team['name'],
                    'logo' => $team['logo']
                ]
            );
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($newTeam,
            Response::HTTP_CREATED);
    }
}