<?php

namespace App\Event;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Symfony\Component\HttpKernel\KernelEvents;

class SortieEventSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
//            KernelEvents::REQUEST => 'verifierEtatToutesSorties',
            SortieEvent::INSCRIPTION => 'verifierEtatUneSortie',
            SortieEvent::DESISTEMENT => 'verifierEtatUneSortie',

        ];
    }

//    public function verifierEtatToutesSorties(GetResponseEvent $event, SortieRepository $sortieRepository)
//    {
//        //TODO : récupérer les sorties dont l'etat est 'Ouverte' ou 'En cours' et vérifier si besoin de changer (date, nb inscrit.e.s)
//    }

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
            $sortie->setEtat($etatRepository->findOneBy(['Clôturée']));
        }
        if($sortie->getEtat()->getLibelle() == 'Clôturée'
        && !$nbMaxParticipantsAtteint
        && $sortie->getDateLimiteInscription() >= $today)
        {
            $sortie->setEtat($etatRepository->findOneBy(['Ouverte']));
        }
    }
}