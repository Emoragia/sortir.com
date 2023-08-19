<?php

namespace App\Data;


use App\Entity\Campus;
use App\Entity\Participant;

class SortieRechercheData
{
    public Participant $participant;
    public ?Campus $campus = null;
    public ?string $nomRecherche = null;
    public ?\DateTime $borneDateInf = null;
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