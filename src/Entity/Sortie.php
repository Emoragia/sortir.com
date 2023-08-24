<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $idSortie = null;

    #[Assert\Regex('/^\w+/')]
    #[Assert\Length(
        min: 5,
        max: 80,
        minMessage: 'Le nom doit contenir au moins 5 caractères alphanumériques',
        maxMessage: 'le nom ne peut pas contenir plus de 80 caractères.'
    )]
    #[Assert\NotBlank(message: 'Veuillez saisir un nom pour votre sortie s\'il vous plaît.')]
    #[ORM\Column(length: 80)]
    private ?string $nom = null;

//    #[Assert\DateTime()]
    #[Assert\NotBlank(message: 'Veuillez renseignez la date de la sortie.')]
    #[Assert\GreaterThan('today')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;


    #[Assert\Positive(message: 'La durée doit être un nombre entier positif et supérieur à 0.')]
    #[Assert\LessThanOrEqual(1440, message: 'La durée ne peut excéder 1440 minutes (24h).')]
    #[ORM\Column]
    private ?int $duree = null;

    #[Assert\LessThan(propertyPath: 'dateHeureDebut')]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[Assert\Regex('/^[1-9]{1}\d?$/', message: 'Le nombres de participant.e.s doit être compris entre 1 et 99')]
    #[ORM\Column]
    private ?int $nbInscriptionsMax = null;

    #[Assert\Regex('/^\w+/')]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'La description doit contenir au moins 20 caractères alphanumériques',
        maxMessage: 'la description ne peut pas contenir plus de 255 caractères.'
    )]
    #[Assert\NotBlank(message: 'Veuillez saisir un nom pour votre sortie s\'il vous plaît.')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $infosSortie = null;

    #[ORM\ManyToOne(inversedBy: 'sortiesOrganisees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Participant $organisateur = null;

    #[ORM\ManyToMany(targetEntity: Participant::class, mappedBy: 'sortiesSuivies')]
    private Collection $participants;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $siteOrganisateur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->idSortie;
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

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): static
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): static
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): static
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getOrganisateur(): ?Participant
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Participant $organisateur): static
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->addSortiesSuivies($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            $participant->removeSortiesSuivies($this);
        }

        return $this;
    }

    public function getSiteOrganisateur(): ?Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Campus $siteOrganisateur): static
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): static
    {
        $this->etat = $etat;

        return $this;
    }
}
