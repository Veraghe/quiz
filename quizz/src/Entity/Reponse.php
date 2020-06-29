<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
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
    private $libelleReponse;

    /**
     * @ORM\Column(type="integer")
     */
    private $valeurReponse;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity=ReponseUtilisateur::class, mappedBy="Reponse")
     */
    private $reponseUtilisateurs;

    public function __construct()
    {
        $this->reponseUtilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleReponse(): ?string
    {
        return $this->libelleReponse;
    }

    public function setLibelleReponse(string $libelleReponse): self
    {
        $this->libelleReponse = $libelleReponse;

        return $this;
    }

    public function getValeurReponse(): ?int
    {
        return $this->valeurReponse;
    }

    public function setValeurReponse(int $valeurReponse): self
    {
        $this->valeurReponse = $valeurReponse;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection|ReponseUtilisateur[]
     */
    public function getReponseUtilisateurs(): Collection
    {
        return $this->reponseUtilisateurs;
    }

    public function addReponseUtilisateur(ReponseUtilisateur $reponseUtilisateur): self
    {
        if (!$this->reponseUtilisateurs->contains($reponseUtilisateur)) {
            $this->reponseUtilisateurs[] = $reponseUtilisateur;
            $reponseUtilisateur->setReponse($this);
        }

        return $this;
    }

    public function removeReponseUtilisateur(ReponseUtilisateur $reponseUtilisateur): self
    {
        if ($this->reponseUtilisateurs->contains($reponseUtilisateur)) {
            $this->reponseUtilisateurs->removeElement($reponseUtilisateur);
            // set the owning side to null (unless already changed)
            if ($reponseUtilisateur->getReponse() === $this) {
                $reponseUtilisateur->setReponse(null);
            }
        }

        return $this;
    }
    public function __toString() {
    return $this->getLibelleReponse();
    }
}
