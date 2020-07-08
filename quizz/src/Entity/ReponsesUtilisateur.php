<?php

namespace App\Entity;

use App\Repository\ReponsesUtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponsesUtilisateurRepository::class)
 */
class ReponsesUtilisateur
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
    private $utilisateur;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $reponse0 = [];

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $reponse1 = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(string $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getReponse0(): ?array
    {
        return $this->reponse0;
    }

    public function setReponse0(?array $reponse0): self
    {
        $this->reponse0 = $reponse0;

        return $this;
    }

    public function getReponse1(): ?array
    {
        return $this->reponse1;
    }

    public function setReponse1(?array $reponse1): self
    {
        $this->reponse1 = $reponse1;

        return $this;
    }
}
