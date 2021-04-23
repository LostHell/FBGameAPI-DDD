<?php

namespace App\Domain\Team;

use App\Domain\Game\Game;
use App\Infrastructure\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 * @UniqueEntity(fields={"name"})
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min=4, max=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private string $logo;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="homeTeam")
     */
    private Collection $homeTeam;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="awayTeam")
     */
    private Collection $awayTeam;

    public function __construct()
    {
        $this->homeTeam = new ArrayCollection();
        $this->awayTeam = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getHomeTeam(): Collection
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(Game $homeTeam): self
    {
        if (!$this->homeTeam->contains($homeTeam)) {
            $this->homeTeam[] = $homeTeam;
            $homeTeam->setGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getAwayTeam(): Collection
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(Game $awayTeam): self
    {
        $this->awayTeam = $awayTeam;
        if (!$this->awayTeam->contains($awayTeam)) {
            $this->awayTeam[] = $awayTeam;
            $awayTeam->setGame($this);
        }

        return $this;
    }
}
