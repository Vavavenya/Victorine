<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pub_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="id_quiz")
     */
    private $no;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="id_quiz")
     */
    private $players;

    public function __construct()
    {
        $this->no = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getPubDate(): ?\DateTimeInterface
    {
        return $this->pub_date;
    }

    public function setPubDate(\DateTimeInterface $pub_date): self
    {
        $this->pub_date = $pub_date;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getNo(): Collection
    {
        return $this->no;
    }

    public function addNo(Question $no): self
    {
        if (!$this->no->contains($no)) {
            $this->no[] = $no;
            $no->setIdQuiz($this);
        }

        return $this;
    }

    public function removeNo(Question $no): self
    {
        if ($this->no->contains($no)) {
            $this->no->removeElement($no);
            // set the owning side to null (unless already changed)
            if ($no->getIdQuiz() === $this) {
                $no->setIdQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Player[]
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): self
    {
        if (!$this->players->contains($player)) {
            $this->players[] = $player;
            $player->setIdQuiz($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getIdQuiz() === $this) {
                $player->setIdQuiz(null);
            }
        }

        return $this;
    }
}
