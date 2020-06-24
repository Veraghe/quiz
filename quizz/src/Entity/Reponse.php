<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
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
}
