<?php

namespace App\Entity;

use App\Repository\QuestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionnaireRepository::class)
 */
class Questionnaire
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
    private $libelleQuestionnaire;


    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="Questionnaire")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity=ReponsesUtilisateur::class, mappedBy="questionnaire")
     */
    private $reponsesUtilisateurs;

    public function __construct()
    {
        $this->typeDeQuestions = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->reponsesUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleQuestionnaire(): ?string
    {
        return $this->libelleQuestionnaire;
    }

    public function setLibelleQuestionnaire(string $libelleQuestionnaire): self
    {
        $this->libelleQuestionnaire = $libelleQuestionnaire;

        return $this;
    }


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
                $question->setQuestionnaire($this);
            }

            return $this;
        }

        public function removeQuestion(Question $question): self
        {
            if ($this->questions->contains($question)) {
                $this->questions->removeElement($question);
                // set the owning side to null (unless already changed)
                if ($question->getQuestionnaire() === $this) {
                    $question->setQuestionnaire(null);
                }
            }

            return $this;
        }

        public function __toString() {
        return $this->getLibelleQuestionnaire();
        }

        /**
         * @return Collection|ReponsesUtilisateur[]
         */
        public function getReponsesUtilisateurs(): Collection
        {
            return $this->reponsesUtilisateurs;
        }

        public function addReponsesUtilisateur(ReponsesUtilisateur $reponsesUtilisateur): self
        {
            if (!$this->reponsesUtilisateurs->contains($reponsesUtilisateur)) {
                $this->reponsesUtilisateurs[] = $reponsesUtilisateur;
                $reponsesUtilisateur->setQuestionnaire($this);
            }

            return $this;
        }

        public function removeReponsesUtilisateur(ReponsesUtilisateur $reponsesUtilisateur): self
        {
            if ($this->reponsesUtilisateurs->contains($reponsesUtilisateur)) {
                $this->reponsesUtilisateurs->removeElement($reponsesUtilisateur);
                // set the owning side to null (unless already changed)
                if ($reponsesUtilisateur->getQuestionnaire() === $this) {
                    $reponsesUtilisateur->setQuestionnaire(null);
                }
            }

            return $this;
        }
}
