<?php

namespace App\Data;


use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Component\Validator\Constraints as Assert;

class SortieRechercheData
{
    public Participant $participant;
    public ?Campus $campus = null;
    #[Assert\Regex('/^\w+$/')]
    #[Assert\Length(
        min: 6,
        max: 80,
        minMessage: 'Le nom recherché doit contenir au moins 6 caractères alphanumériques',
        maxMessage: 'le nom recherché ne peut pas contenir plus de 80 caractères.'
    )]
    public ?string $nomRecherche = null;
    #[Assert\GreaterThanOrEqual('today')]
    public ?\DateTime $borneDateInf = null;
    #[Assert\GreaterThanOrEqual(propertyPath: 'borneDateInf')]
    public ?\DateTime $borneDateSup = null;
    public bool $organisateur = false;
    public bool $inscrit = false;
    public bool $nonInscrit = false;
    public bool $sortiesPassees = false;

    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

}