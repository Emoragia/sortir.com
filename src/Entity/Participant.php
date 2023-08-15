<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[UniqueEntity(fields: 'email',message: "Un compte est déjà associé à cet email")]
#[UniqueEntity(fields: 'username',message: "Ce pseudo est déjà utilisé par un compte")]
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idParticipant = null;
    #[Assert\Regex('/^\w+$/')]
    #[Assert\Length(
        min: 6,
        max: 30,
        minMessage: 'Votre pseudo doit contenir au moins 6 caractères alphanumériques',
        maxMessage: 'Votre pseudo ne peut pas contenir plus de 30 caractères.'
    )]
    #[Assert\NotBlank(message: 'N\'oubliez pas saisir votre pseudo !')]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\Regex('/^\w+$/')]
    #[Assert\Length(
        min: 6,
        max: 50,
        minMessage: 'Votre nom doit contenir au moins 6 caractères alphanumériques',
        maxMessage: 'Votre nom ne peut pas contenir plus de 50 caractères.'
    )]
    #[Assert\NotBlank(message: 'N\'oubliez pas saisir votre nom !')]
    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[Assert\Regex('/^\w+$/')]
    #[Assert\Length(
        min:3,
        max: 30,
        minMessage: 'Votre prénom doit contenir au moins 3 caractères alphanumériques',
        maxMessage: 'Votre nom ne peut pas contenir plus de 30 caractères.'
    )]
    #[Assert\NotBlank(message: 'N\'oubliez pas saisir votre prénom !')]
    #[ORM\Column(length: 30)]
    private ?string $prenom = null;

    #[Assert\Regex('/^(0\d)(\d{2}){4}/', message: 'Format invalide (ex.: 0687955463')]
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[Assert\Regex('/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\\.[A-Za-z]{2,}$/',
    message: 'Format d\'adresse mail invalide. (ex.: test@mail.com)')]
    #[ORM\Column(length: 80, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[Assert\Regex('/^[\w\W]{4,}/',
    message: 'Format invalide (caractères alpahnumériques ou spéciaux)')]
    #[Assert\Length(
        min: 4,
        minMessage: 'Votre mot de passe doit contenir au moins 4 caractères.'
    )]
    private ?string $motPasseClair = null;

    public function getId(): ?int
    {
        return $this->idParticipant;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_PARTICIPANT';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
         $this->motPasseClair = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isAdministrateur(): ?bool
    {
        return $this->administrateur;
    }

    public function setAdministrateur(bool $administrateur): static
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getMotPasseClair(): ?string
    {
        return $this->motPasseClair;
    }

    public function setMotPasseClair(?string $motPasseClair): static
    {
        $this->motPasseClair = $motPasseClair;

        return $this;
    }
}
