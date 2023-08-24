<?php

namespace App\Event;

use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;

class SortieEventSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

    public function __construct(private readonly EtatRepository $etatRepository, private readonly EntityManagerInterface $entityManager)
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [
            SortieEvent::INSCRIPTION => 'verifierEtatUneSortie',
            SortieEvent::DESISTEMENT => 'verifierEtatUneSortie',

        ];
    }

    /**
     * Vérifie et modifie l'état d'une sortie après un inscription/désistement en fonction de le date limite d'inscription et du nombre max de participant.e.s.
     * @param SortieEvent $sortieEvent
     * @return void
     */
    public function verifierEtatUneSortie(
        SortieEvent $sortieEvent):void
    {
        $sortie = $sortieEvent->getSortie();
        $today = new \DateTime('now');
        $nbMaxParticipantsAtteint = count($sortie->getParticipants())== $sortie->getNbInscriptionsMax();
        if($sortie->getEtat()->getLibelle() == 'Ouverte' &&  $nbMaxParticipantsAtteint){
            $sortie->setEtat($this->etatRepository->findOneBy(['libelle'=>'Clôturée']));
            $this->entityManager->persist($sortie);
            $this->entityManager->flush();
        }
        if($sortie->getEtat()->getLibelle() == 'Clôturée'
        && !$nbMaxParticipantsAtteint
        && $sortie->getDateLimiteInscription() >= $today)
        {
            $sortie->setEtat($this->etatRepository->findOneBy(['libelle'=>'Ouverte']));
            $this->entityManager->persist($sortie);
            $this->entityManager->flush();
        }
    }
}