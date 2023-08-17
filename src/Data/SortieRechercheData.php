<?php

namespace App\Data;


use App\Entity\Campus;
use App\Entity\Participant;

class SortieRechercheData
{
    protected Participant $participant;
    protected Campus $campus;
    protected string $nomRecherche = '';
    protected \DateTime $borneDateInf;
    protected \DateTime $borneDateSup ;
    protected bool $organisateur = true;
    protected bool $inscrit = true;
    protected bool $nonInscrit = true;
    protected bool $sortiesPassées = false;



}