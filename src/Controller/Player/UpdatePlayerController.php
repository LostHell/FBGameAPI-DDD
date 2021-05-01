<?php

namespace App\Controller\Player;

use App\Application\Handler\PlayerHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdatePlayerController extends AbstractController
{
    private PlayerHandler $handler;

    public function __construct(PlayerHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function update(Request $request): Response
    {
        $playerArray = json_decode($request->getContent(), true);

        try {
            $playerData = $this->handler->handlerUpdate([
                "username" => $playerArray['username'],
                "email" => $playerArray['email'],
                "avatar" => $playerArray['avatar']
            ]);

        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_MODIFIED);
        }

        return new JsonResponse($playerData,
            Response::HTTP_OK);
    }
}