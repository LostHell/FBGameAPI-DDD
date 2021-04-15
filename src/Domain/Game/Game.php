<?php

namespace App\Domain\Game;

use App\Domain\Player\Guess;
use App\Domain\Team\Team;
use App\Infrastructure\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $gameTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\ManyToOne (targetEntity=Team::class, inversedBy="homeTeam")
     */
    private $homeTeam;

    /**
     * @ORM\ManyToOne (targetEntity=Team::class, inversedBy="awayTeam")
     */
    private $awayTeam;

    /**
     * @ORM\OneToMany(targetEntity=Guess::class, mappedBy="game")
     */
    private $guesses;

    public function __construct()
    {
        $this->guesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameTime(): ?\DateTimeInterface
    {
        return $this->gameTime;
    }

    public function setGameTime(\DateTimeInterface $gameTime): self
    {
        $this->gameTime = $gameTime;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }


    public function setHomeTeam(Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;

        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }


    public function setAwayTeam(Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;

        return $this;
    }

    /**
     * @return Collection|Guess[]
     */
    public function getGuesses(): Collection
    {
        return $this->guesses;
    }

    public function addGuess(Guess $guess): self
    {
        if (!$this->guesses->contains($guess)) {
            $this->guesses[] = $guess;
            $guess->setGame($this);
        }

        return $this;
    }

    public function removeGuess(Guess $guess): self
    {
        if ($this->guesses->removeElement($guess)) {
            // set the owning side to null (unless already changed)
            if ($guess->getGame() === $this) {
                $guess->setGame(null);
            }
        }

        return $this;
    }
}
