<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Event\SortieEvent;
use App\Event\SortieEventSubscriber;
use App\Form\AnnulerSortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sorties', name: 'sortie_list', methods: 'GET')]
    public function list(): Response
    {
        return $this->render('main/accueil.html.twig');
    }
    

    #[Route('/sorties/details/{id}', name: 'sortie_details', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function afficherSortie(): Response{

        return $this->render('main/accueil.html.twig');
    }

    #[Route('/sorties/modifier', name: 'sortie_modifier', methods: ['GET', 'POST'])]
    public function modifierSortie(){
        //TODO: renvoi vers une page modifier.html.twig
    }

    #[Route('/sorties/supprimer', name: 'sortie_supprimer', methods: ['GET, DELETE'])]
    public function supprimerSortie(){
        //TODO : renvoi vers la page d'accueil
    }
    #[Route('/sorties/annuler/{id}', name: 'sortie_annuler', requirements: ['id' => '\d+'], methods: ['GET','POST'])]
    public function annulerSortie(
        Request $request,
        int $id,
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        EntityManagerInterface $entityManager): Response
    {
        $sortie = $sortieRepository->find($id);
        $today = new \DateTime('now');
        $annulerForm = $this->createForm(AnnulerSortieType::class, $sortie);
        $annulerForm->handleRequest($request);
        if($annulerForm->isSubmitted() && $annulerForm->isValid())
        {
            if($sortie->getEtat()->getLibelle()!='En cours' && $sortie->getDateHeureDebut() > $today)
            {
                $sortie->setEtat($etatRepository->findOneBy(['libelle'=>'Annulée']));
                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash('success','La sortie a été annulée.');
            }
            else
            {
                $this->addFlash('danger', 'Vous ne pouvez pas annuler cette sortie (elle est en cours).');
            }
            return $this->redirectToRoute('main_accueil');
        }
        return $this->render('sortie/annuler.html.twig', [
            'annulerForm' => $annulerForm->createView()
        ]);
    }
    #[Route('/sortie/inscription/{id}', name: 'sortie_inscription', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function inscrire(
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        int $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $dispatcher = new EventDispatcher();
        $subscriber = new SortieEventSubscriber($etatRepository, $entityManager, $sortieRepository);
        $dispatcher->addSubscriber($subscriber);
        $sortie = $sortieRepository->find($id);
        /** @var Participant $participant */
        $participant = $this->getUser();
        $today = new \DateTime('now');
        $inscriptionPossible = $sortie
            && $sortie->getEtat()->getLibelle()=='Ouverte'
            && $sortie->getDateLimiteInscription() >= $today
            && count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax();
        if($inscriptionPossible)
        {
            $sortie->addParticipant($participant);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $inscriptionEvent = new SortieEvent($sortie);
            $dispatcher->dispatch($inscriptionEvent, SortieEvent::INSCRIPTION);
            $this->addFlash('success', 'Votre inscription a été prise en compte.');
        }
        else
        {
            $this->addFlash('warning', 'Votre inscription n\'a pas pu être prise en compte (l\'organisateur.ice a annulé la sortie, le nombre maximum de participant.e.s est atteint ou la date limite d\'inscription est dépassée).');
        }
    //TODO : éventuellement, rediriger vers la page du détail de la sortie en cas de susccès de l'inscription ?
        return $this->redirectToRoute('main_accueil');
    }
    #[Route('/sortie/desistement/{id}', name:'sortie_desistement', requirements: ['id' => '\d+'], methods: ["GET"])]
    public function seDesister(
        SortieRepository $sortieRepository,
        EtatRepository $etatRepository,
        int $id,
        EntityManagerInterface $entityManager
    ):Response
    {
        $dispatcher = new EventDispatcher();
        $subscriber = new SortieEventSubscriber($etatRepository, $entityManager, $sortieRepository);
        $dispatcher->addSubscriber($subscriber);
        $sortie = $sortieRepository->find($id);
        /** @var Participant $participant */
        $participant = $this->getUser();
        $today = new \DateTime('now');
        $desincriptionPossible = $sortie
            && ($sortie->getEtat()->getLibelle() == 'Ouverte' | $sortie->getEtat()->getLibelle() == 'Clôturée')
            && $sortie->getDateHeureDebut() > $today;
        if($desincriptionPossible)
        {
            $sortie->removeParticipant($participant);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $inscriptionEvent = new SortieEvent($sortie);
            $dispatcher->dispatch($inscriptionEvent, SortieEvent::DESISTEMENT);
            $this->addFlash('success', 'Vous vous êtes désinscrit.e de la sortie.');
        }
        else
        {
            $this->addFlash('warning', 'Vous ne pouvez pas vous désinscrire de cette sortie.');
        }

        return $this->redirectToRoute('main_accueil');
    }
}
