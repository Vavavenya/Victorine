<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

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
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $pub_date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $num_players;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="quiz")
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Player", mappedBy="quiz")
     */
    private $players;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Leaders", mappedBy="quiz")
     *  @OrderBy({"correct" = "DESC"})
     */
    private $leaders;




    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->leaders = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function  getNumPlayers(): ?int
    {
        return $this->num_players;
    }

    public function setNumPlayers(?int $num_players): self
    {
        $this->num_players = $num_players;

        return $this;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
    public function getQuestion(): Collection
    {
        return $this->question;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->question->contains($question)) {
            $this->question[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->question->contains($question)) {
            $this->question->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
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
            $player->setQuiz($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): self
    {
        if ($this->players->contains($player)) {
            $this->players->removeElement($player);
            // set the owning side to null (unless already changed)
            if ($player->getQuiz() === $this) {
                $player->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Leaders[]
     */
    public function getLeaders(): Collection
    {
        return $this->leaders;
    }

    public function addLeader(Leaders $leader): self
    {
        if (!$this->leaders->contains($leader)) {
            $this->leaders[] = $leader;
            $leader->setQuiz($this);
        }

        return $this;
    }

    public function removeLeader(Leaders $leader): self
    {
        if ($this->leaders->contains($leader)) {
            $this->leaders->removeElement($leader);
            // set the owning side to null (unless already changed)
            if ($leader->getQuiz() === $this) {
                $leader->setQuiz(null);
            }
        }

        return $this;
    }
}
