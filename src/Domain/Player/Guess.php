<?php

namespace App\Domain\Player;

use App\Domain\Game\Game;
use App\Infrastructure\Repository\GuessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuessRepository::class)
 */
class Guess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $guess;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class, inversedBy="guesses")
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Player::class, inversedBy="guesses")
     */
    private $player;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuess(): ?string
    {
        return $this->guess;
    }

    public function setGuess(string $guess): self
    {
        $this->guess = $guess;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }
}
