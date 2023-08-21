<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $idLieu = null;

    #[Assert\Regex('/^\w+/')]
    #[Assert\Length(
        min: 5,
        max: 80,
        minMessage : 'Minimum 5 caractères s\'íl vous plaît',
        maxMessage: 'Maximum 80 caractères s\'il vous plaît'
    )]
    #[Assert\NotBlank(message:'Renseignez un nom s\'il vous plaît')]
    #[ORM\Column(length: 80)]
    private ?string $nom = null;

    #[Assert\Regex('/^\w+/')]
    #[Assert\Length(
        min: 5,
        max: 150,
        minMessage : 'Minimum 5 caractères s\'íl vous plaît',
        maxMessage: 'Maximum 150 caractères s\'il vous plaît'
    )]
    #[Assert\NotBlank(message:'Renseignez la rue s\'il vous plaît')]
    #[ORM\Column(length: 150)]
    private ?string $rue = null;

    #[Assert\Regex('/^-?\d+[\,\.]\d+$/')]
    #[Assert\Length(max: 255,maxMessage: 'Ce champ ne peut dépasser 255 caractères.')]
    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[Assert\Regex('/^-?\d+[\,\.]\d+&/')]
    #[Assert\Length(max: 255,maxMessage: 'Ce champ ne peut dépasser 255 caractères.')]
    #[ORM\Column(nullable: true)]
    private ?float $longitude = null;

    #[ORM\ManyToOne(targetEntity: Ville::class, inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ville $ville = null;

    public function getId(): ?int
    {
        return $this->idLieu;
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }
    public function __toString(): string
    {
        return $this->nom. " " .$this->ville->getCodePostal(). " " . $this->ville->getNom();
    }
}
