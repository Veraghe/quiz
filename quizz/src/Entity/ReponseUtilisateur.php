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
     * @ORM\JoinColumn(nullable=false)
     */
    private $Reponse;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="reponseUtilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Utilisateur;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponseUtilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Question;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

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

}
