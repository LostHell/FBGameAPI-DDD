<?php

namespace App\Controller\Guess;

use App\Application\Handler\GuessHandler;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GetGuessController
 * @package App\Controller\Guess
 */
class GetGuessController extends AbstractController
{
    private GuessHandler $handler;

    public function __construct(GuessHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @return Response
     * @throws Exception
     */
    public function getAllGuesses(): Response
    {
        try {
            $guesses = $this->handler->handlerGetAllGuesses();
        } catch (Exception $ex) {
            return new JsonResponse(['message' => $ex->getMessage()],
                Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($guesses->toArray(),
            Response::HTTP_OK);
    }
}