<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 */
class Session
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
    private $libelleSession;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleSession(): ?string
    {
        return $this->libelleSession;
    }

    public function setLibelleSession(string $libelleSession): self
    {
        $this->libelleSession = $libelleSession;

        return $this;
    }
}
