<?php

namespace App\Application\Handler;

use App\Infrastructure\Repository\GuessRepository;

class GuessHandler
{
    private GuessRepository $guessRepository;

    public function __construct(GuessRepository $guessRepository)
    {
        $this->guessRepository = $guessRepository;
    }
}