<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchesRepository")
 */
class Matches
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $id_team1;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $id_team2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $winner;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdTeam1(): ?string
    {
        return $this->id_team1;
    }

    public function setIdTeam1(string $id_team1): self
    {
        $this->id_team1 = $id_team1;

        return $this;
    }

    public function getIdTeam2(): ?string
    {
        return $this->id_team2;
    }

    public function setIdTeam2(string $id_team2): self
    {
        $this->id_team2 = $id_team2;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function setWinner(string $winner): self
    {
        $this->winner = $winner;

        return $this;
    }
}
