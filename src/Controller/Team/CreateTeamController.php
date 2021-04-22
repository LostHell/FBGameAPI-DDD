<?php

namespace App\Controller\Team;

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
    public function __construct()
    {

    }

    public function create(Request $request): Response
    {
        $team = json_decode($request->getContent(), true);

        return new JsonResponse($team);
    }
}