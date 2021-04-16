<?php

namespace App\Controller\Player;

use App\Application\PlayerHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GetPlayerController
 * @package App\Controller\Player
 */
class GetPlayerController extends AbstractController
{
    private PlayerHandler $handler;

    public function __construct(PlayerHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param int $id
     * @return Response
     * @throws Exception
     */
    public function getById(int $id): Response
    {
        try {
            $player = $this->handler->handleGetById($id);
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return new JsonResponse($player);
    }
}