<?php

namespace App\Entity;

use App\Repository\QuizImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizImageRepository::class)
 */
class QuizImage
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
    private $image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reponseImage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getReponseImage(): ?string
    {
        return $this->reponseImage;
    }

    public function setReponseImage(string $reponseImage): self
    {
        $this->reponseImage = $reponseImage;

        return $this;
    }
}
