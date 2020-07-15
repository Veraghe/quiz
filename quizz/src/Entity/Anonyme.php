<?php

namespace App\Entity;

use App\Repository\AnonymeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnonymeRepository::class)
 */
class Anonyme
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
    private $Email;

    /**
     * @ORM\OneToMany(targetEntity=ReponseUtilisateur::class, mappedBy="Anonyme")
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

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

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
            $reponseUtilisateur->setAnonyme($this);
        }

        return $this;
    }

    public function removeReponseUtilisateur(ReponseUtilisateur $reponseUtilisateur): self
    {
        if ($this->reponseUtilisateurs->contains($reponseUtilisateur)) {
            $this->reponseUtilisateurs->removeElement($reponseUtilisateur);
            // set the owning side to null (unless already changed)
            if ($reponseUtilisateur->getAnonyme() === $this) {
                $reponseUtilisateur->setAnonyme(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->getEmail();
        }
}
