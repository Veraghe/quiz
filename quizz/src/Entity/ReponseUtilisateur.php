<?php

namespace App\Entity;

use App\Repository\ReponseUtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseUtilisateurRepository::class)
 */
class ReponseUtilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reponse::class, inversedBy="reponseUtilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Reponse;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="reponseUtilisateurs")
     */
    private $Utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponseUtilisateurs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Question;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Anonyme::class, inversedBy="reponseUtilisateurs")
     */
    private $Anonyme;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $reponseImage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $reponseTextarea;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?Reponse
    {
        return $this->Reponse;
    }

    public function setReponse(?Reponse $Reponse): self
    {
        $this->Reponse = $Reponse;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?Utilisateur $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function __toString() {
        return $this->getDate();
        }

    public function getAnonyme(): ?Anonyme
    {
        return $this->Anonyme;
    }

    public function setAnonyme(?Anonyme $Anonyme): self
    {
        $this->Anonyme = $Anonyme;

        return $this;
    }

    public function getImage(): ?int
    {
        return $this->image;
    }

    public function setImage(?int $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getReponseImage(): ?int
    {
        return $this->reponseImage;
    }

    public function setReponseImage(?int $reponseImage): self
    {
        $this->reponseImage = $reponseImage;

        return $this;
    }

    public function getReponseTextarea(): ?string
    {
        return $this->reponseTextarea;
    }

    public function setReponseTextarea(?string $reponseTextarea): self
    {
        $this->reponseTextarea = $reponseTextarea;

        return $this;
    }

}
