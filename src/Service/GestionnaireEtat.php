<?php

namespace App\Service;

use App\Entity\Sortie;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

class GestionnaireEtat
{
    public function __construct(private readonly SortieRepository $sortieRepository,
                                private readonly EtatRepository $etatRepository,
                                private readonly EntityManagerInterface $entityManager)
    {
    }
    public function gererEtats(): int
    {
        $compteur = 0;
        /** @var Sortie[] $sortiesOuvertes */
        $sortiesOuvertes = $this->sortieRepository->findSortiesByStateAndDate('Ouverte');
        $today = new \DateTime('now');
//        dd($sortiesOuvertes);

        //On vérifie si les sorties ouvertes doivent  être 'Clôturée' ou être enregistrée comme 'Activité en cours'
        if(!empty($sortiesOuvertes))
        {
//            dd($sortiesOuvertes);
            foreach ($sortiesOuvertes as $sortie)
            {
                    $sortie->setEtat($this->etatRepository->findOneBy(['libelle'=>'Clôturée']));
                    $this->entityManager->persist($sortie);
                    $this->entityManager->flush();
                    $compteur+=1;
            }
        }
        /** @var Sortie[] $sortiesCloturees */
        $sortiesCloturees = $this->sortieRepository->findSortiesByStateAndDate('Clôturée');
//        dd($sortiesCloturees);
        //On vérifie si les sorties clôturées doivent être enregistrées en 'Activité en cours'
        if(!empty($sortiesCloturees))
        {
//            dd($sortiesOuvertes);
            foreach ($sortiesCloturees as $sortie)
            {
                    $sortie->setEtat($this->etatRepository->findOneBy(['libelle'=>'Activité en cours']));
                    $this->entityManager->persist($sortie);
                    $this->entityManager->flush();
                    $compteur+=1;
            }
        }
        /** @var Sortie[] $sortiesEnCours */
        $sortiesEnCours = $this->sortieRepository->findSortiesByStateAndDate('Activité en cours');
        //On vérifie si les sorties en cours doivent être enregistrées comme 'Passées'
        if(!empty($sortiesEnCours))
        {
//            dd($sortiesEnCours);
            foreach ($sortiesEnCours as $sortie)
            {
//                dd($sortie->getDateHeureDebut());
                    $sortie->setEtat($this->etatRepository->findOneBy(['libelle'=>'Passée']));
                    $this->entityManager->persist($sortie);
                    $this->entityManager->flush();
                    $compteur+=1;

            }
        }
        /** @var Sortie[] $sortiesPasseesAnnulees */
        $sortiesPassees = $this->sortieRepository->findSortiesByStateAndDate('Passée');
        $sortiesAnnulees = $this->sortieRepository->findSortiesByStateAndDate('Annulée');
        $sortiesPasseesAnnulees = array_merge($sortiesPassees, $sortiesAnnulees);
//        dd($sortiesPasseesAnnulees);
        //On récupère  les sorties passées ou annulées dont la date de début prévue est ancienne d'au moins un mois (31 jours) pour les passer en état 'Archivée'
        if(!empty($sortiesPasseesAnnulees))
        {
            foreach ($sortiesPasseesAnnulees as $sortie)
            {
                $sortie->setEtat($this->etatRepository->findOneBy(['libelle'=>'Archivée']));
                $this->entityManager->persist($sortie);
                $this->entityManager->flush();
                $compteur+=1;
            }
        }
        return $compteur;
    }
}