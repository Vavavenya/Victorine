<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlayerRepository")
 */
class Player
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="players")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="players")
     */
    private $id_question;

    public function getId()
    {
        return $this->id;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getIdQuiz(): ?Quiz
    {
        return $this->id_quiz;
    }

    public function setIdQuiz(?Quiz $id_quiz): self
    {
        $this->id_quiz = $id_quiz;

        return $this;
    }

    public function getIdQuestion(): ?Question
    {
        return $this->id_question;
    }

    public function setIdQuestion(?Question $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }
}
