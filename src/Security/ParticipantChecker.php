<?php

namespace App\Security;

use App\Entity\Participant;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;

class ParticipantChecker implements \Symfony\Component\Security\Core\User\UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(Participant|UserInterface $participant) : void
    {
        if(!$participant->isActif())
        {
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas actif, contacter un.e administrateur.ice pour plus d\'informations');
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(Participant|UserInterface $participant) : void
    {
        if(!$participant->isActif())
        {
            throw new CustomUserMessageAccountStatusException('Votre compte n\'est pas actif, contacter un.e administrateur.ice pour plus d\'informations');
        }
    }
}