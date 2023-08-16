<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Message;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
#[UniqueEntity(fields: 'email',message: "Un compte est déjà associé à cet email")]
#[UniqueEntity(fields: 'pseudo',message: "Ce pseudo est déjà utilisé par un compte")]
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $idParticipant = null;
    #[Assert\Regex('/^\w+$/')]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Votre pseudo doit contenir au moins 6 caractères alphanumériques',
        maxMessage: 'Votre pseudo ne peut pas contenir plus de 30 caractères.'
    )]
    #[Assert\NotBlank(message: 'N\'oubliez pas saisir votre pseudo !')]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $pseudo = null;

    /**
     * @var string The hashed password
     */
    #[Assert\Regex('/^[\w\W]{4,}/',
        message: 'Format invalide (Utilisez des caractères alpahnumériques ou spéciaux)')]
    #[Assert\Length(
        min: 4,
        minMessage: 'Votre mot de passe doit contenir au moins 4 caractères.'
    )]
    #[ORM\Column]
    private ?string $motPasse = null;

    #[Assert\Regex('/^\w+$/')]
    #[Assert\Length(
        min: 3,
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

    #[Assert\Email(message: 'Format d\'adresse mail invalide. (ex.: test@mail.com)')]
    #[ORM\Column(length: 80, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $administrateur = null;

    #[ORM\Column]
    private ?bool $actif = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'participants')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class, orphanRemoval: true)]
    private Collection $sortiesOrganisees;

    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'participants')]
    private Collection $sortiesSuivies;

    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->sortiesSuivies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->idParticipant;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles[]=[];
        if($this->administrateur){
            $roles=['ROLE_ADMIN'];
        }
        else{
            $roles=['ROLE_PARTICIPANT'];
        }
        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->motPasse;
    }
    public function setMotPasse(string $motPasse): string
    {
        $this->motPasse = $motPasse;
        return $this->motPasse;
    }
    public function getMotPasse(): string
    {
        return $this->motPasse;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
//         $this->motPasseClair = null;
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

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisee(Sortie $sortiesOrganisee): static
    {
        if (!$this->sortiesOrganisees->contains($sortiesOrganisee)) {
            $this->sortiesOrganisees->add($sortiesOrganisee);
            $sortiesOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisee(Sortie $sortiesOrganisee): static
    {
        if ($this->sortiesOrganisees->removeElement($sortiesOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganisee->getOrganisateur() === $this) {
                $sortiesOrganisee->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesSuivies(): Collection
    {
        return $this->sortiesSuivies;
    }

    public function addSortiesSuivies(Sortie $sortiesSuivies): static
    {
        if (!$this->sortiesSuivies->contains($sortiesSuivies)) {
            $this->sortiesSuivies->add($sortiesSuivies);
        }

        return $this;
    }

    public function removeSortiesSuivies(Sortie $sortiesSuivies): static
    {
        $this->sortiesSuivies->removeElement($sortiesSuivies);

        return $this;
    }
}
