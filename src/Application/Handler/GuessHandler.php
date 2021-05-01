<?php

namespace App\Application\Handler;

use App\Infrastructure\Repository\GuessRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;

class GuessHandler
{
    private GuessRepository $guessRepository;

    public function __construct(GuessRepository $guessRepository)
    {
        $this->guessRepository = $guessRepository;
    }

    /**
     * @return ArrayCollection
     * @throws Exception
     */
    public function handlerGetAllGuesses(): ArrayCollection
    {
        try {
            $guesses = $this->guessRepository->findAll();
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        $data = new ArrayCollection();

        foreach ($guesses as $guess) {
            $data->add(
                [
                    'player' => $guess->getPlayer()->getUsername(),
                    'guess' => $guess->getGuess(),
                    'game' => $guess->getGame()->getId(),
                    'createdAt' => $guess->getCreatedAt(),
                ]
            );
        }

        return $data;
    }
}