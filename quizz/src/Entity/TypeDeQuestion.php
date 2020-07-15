<?php

namespace App\Entity;

use App\Repository\TypeDeQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeDeQuestionRepository::class)
 */
class TypeDeQuestion
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
    private $libelleTypeDeQuestion;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Questionnaire::class, inversedBy="typeDeQuestions")
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $questionnaire;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="typeDeQuestion")
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleTypeDeQuestion(): ?string
    {
        return $this->libelleTypeDeQuestion;
    }

    public function setLibelleTypeDeQuestion(string $libelleTypeDeQuestion): self
    {
        $this->libelleTypeDeQuestion = $libelleTypeDeQuestion;

        return $this;
    }

    // public function getQuestionnaire(): ?Questionnaire
    // {
    //     return $this->questionnaire;
    // }

    // public function setQuestionnaire(?Questionnaire $questionnaire): self
    // {
    //     $this->questionnaire = $questionnaire;

    //     return $this;
    // }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTypeDeQuestion($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getTypeDeQuestion() === $this) {
                $question->setTypeDeQuestion(null);
            }
        }

        return $this;
    }
    
    public function __toString() {
    return $this->getLibelleTypeDeQuestion();
    }
}
