<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
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
    private $libelleQuestion;

    /**
     * @ORM\ManyToOne(targetEntity=TypeDeQuestion::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeDeQuestion;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="question")
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity=Questionnaire::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Questionnaire;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleQuestion(): ?string
    {
        return $this->libelleQuestion;
    }

    public function setLibelleQuestion(string $libelleQuestion): self
    {
        $this->libelleQuestion = $libelleQuestion;

        return $this;
    }

    public function getTypeDeQuestion(): ?TypeDeQuestion
    {
        return $this->typeDeQuestion;
    }

    public function setTypeDeQuestion(?TypeDeQuestion $typeDeQuestion): self
    {
        $this->typeDeQuestion = $typeDeQuestion;

        return $this;
    }
    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->Questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $Questionnaire): self
    {
        $this->Questionnaire = $Questionnaire;

        return $this;
    }
    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }
    public function __toString() {
    return $this->getLibelleQuestion();
    }


}
