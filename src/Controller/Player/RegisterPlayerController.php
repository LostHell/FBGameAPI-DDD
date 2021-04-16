<?php

namespace App\Controller\Player;

use App\Application\PlayerHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterPlayerController
 * @package App\Controller\Player
 */
class RegisterPlayerController extends AbstractController
{
    private PlayerHandler $handler;

    public function __construct(PlayerHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $playerArray = json_decode($request->getContent(), true);

        try {
            $errors = $this->handler->handleRegister(
                [
                    'username' => $playerArray['username'],
                    'email' => $playerArray['email'],
                    'avatar' => $playerArray['avatar'],
                    'password' => $playerArray['password'],
                ]
            );

            if ($errors) {
                return $this->json($errors);
            }
        } catch (Exception $ex) {
            return new JsonResponse($ex->getMessage());
        }

        return new JsonResponse('Player created!');
    }
}