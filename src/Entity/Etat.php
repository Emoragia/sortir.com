<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: 'libelle')]
#[ORM\Entity(repositoryClass: EtatRepository::class)]
class Etat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id')]
    private ?int $idEtat = null;

    #[ORM\Column(length: 20, unique : true)]
    #[Assert\Regex('/^\w+/')]
    #[Assert\Length(
        min: 5,
        max: 20,
        minMessage : 'Minimum 5 caractères s\'íl vous plaît',
        maxMessage: 'Maximum 20 caractères s\'il vous plaît'
    )]
    #[Assert\NotBlank(message: 'Le libellé doit être renseigné.')]
    private ?string $libelle = null;

    public function getId(): ?int
    {
        return $this->idEtat;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}
