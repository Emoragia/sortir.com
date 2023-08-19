<?php

namespace App\Event;

use App\Entity\Sortie;
use Symfony\Contracts\EventDispatcher\Event;

class SortieEvent extends Event
{
    public const INSCRIPTION = 'inscription';
    public const DESISTEMENT = 'desistement';

    public function __construct(protected Sortie $sortie)
    {
    }

    /**
     * @return Sortie
     */
    public function getSortie(): Sortie
    {
        return $this->sortie;
    }

}