<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LeadersRepository")
 */
class Leaders
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\quiz", inversedBy="leaders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="leaders", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $correct;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $answered;

    public function getId()
    {
        return $this->id;
    }

    public function getQuiz(): ?quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getCorrect(): ?int
    {
        return $this->correct;
    }

    public function setCorrect(?int $correct): self
    {
        $this->correct = $correct;

        return $this;
    }

    public function getAnswered(): ?int
    {
        return $this->answered;
    }

    public function setAnswered(?int $answered): self
    {
        $this->answered = $answered;

        return $this;
    }
}
