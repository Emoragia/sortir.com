<?php

namespace App\Data;


use App\Entity\Campus;
use App\Entity\Participant;

class SortieRechercheData
{
    public Participant $participant;
    public ?Campus $campus = null;
    public string $nomRecherche = '';
    public ?\DateTime $borneDateInf = null;
    public ?\DateTime $borneDateSup = null;
    public bool $organisateur = true;
    public bool $inscrit = true;
    public bool $nonInscrit = true;
    public bool $sortiesPassees = false;

}