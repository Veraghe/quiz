<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 * @UniqueEntity(
 *  fields= {"email"},
 *  message= "L'email que vous avez indiqué est déjà utilisé !"
 * )
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min="4", minMessage="Votre mot de passe doit faire minimum 4 caractères")
     */
    private $password;

    
    /**
     * @Assert\EqualTo(propertyPath="password", message="Votre mot de passe n'est pas identique à la confirmation")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\OneToMany(targetEntity=ReponseUtilisateur::class, mappedBy="Utilisateur")
     */
    private $reponseUtilisateurs;

    /**
     * @ORM\ManyToOne(targetEntity=Session::class, inversedBy="utilisateurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $session;

    public function __construct()
    {
        $this->reponseUtilisateurs = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

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
            $reponseUtilisateur->setUtilisateur($this);
        }

        return $this;
    }

    public function removeReponseUtilisateur(ReponseUtilisateur $reponseUtilisateur): self
    {
        if ($this->reponseUtilisateurs->contains($reponseUtilisateur)) {
            $this->reponseUtilisateurs->removeElement($reponseUtilisateur);
            // set the owning side to null (unless already changed)
            if ($reponseUtilisateur->getUtilisateur() === $this) {
                $reponseUtilisateur->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function __toString() {
    return $this->getEmail();
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    
}
