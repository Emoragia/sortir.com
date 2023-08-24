<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Sortie;
use App\Event\SortieEvent;
use App\Form\AnnulerSortieType;
use App\Form\CreationSortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SortieController extends AbstractController
{
    #[Route('/sorties/publier/{id}', name: 'sortie_publier', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function publierSortie(int $id,
                                  SortieRepository $sortieRepository,
                                  EtatRepository $etatRepository,
                                  EntityManagerInterface $entityManager): Response
    {
        $sortieAPublier = $sortieRepository->find($id);
        $today = new \DateTime('now');
        if ($sortieAPublier -> getDateLimiteInscription() >= $today && $sortieAPublier->getDateHeureDebut() >= $today ){
            if ($sortieAPublier->getEtat()->getLibelle() =='Créée'){
                $sortieAPublier->setEtat($etatRepository->findOneBy(['libelle'=>'Ouverte']));
                $entityManager->persist($sortieAPublier);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a bien été publié');
            }
        }else{
            $this->addFlash('warning', 'la date inscription ou la date de sortie est dépassé ');
        }

        return $this->redirectToRoute('main_accueil');
    }
    

    #[Route('/sorties/details/{id}', name: 'sortie_details', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function afficherSortie(int $id,
                                   Request $request,
                                   SortieRepository $sortieRepository): Response{
        /**
         * @var Participant $participant
         * */
        $sortieConsulte = $sortieRepository->find($id);
        return $this->render('sortie/details.html.twig', [
            'sortieConsulte'=>$sortieConsulte
        ]);
    }

    #[Route('/sorties/modifier/{id}', name: 'sortie_modifier', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function modifierSortie(Request $request,
                                   int $id,
                                   SortieRepository $sortieRepository,
                                   EntityManagerInterface $entityManager){

        $sortieAModifier = $sortieRepository->find($id);
        $ville = $sortieAModifier->getLieu()->getVille();

        $entityManager->persist($sortieAModifier);
        $entityManager->flush();
        $this ->addFlash('success', 'Votre sortie est bien modifier');
    }

    #[Route('/sorties/supprimer/{id}', name: 'sortie_supprimer', requirements: ['id' => '\d+'], methods: ['GET', 'DELETE'])]
    public function supprimerSortie(int $id,
                                    SortieRepository $sortieRepository,
                                    EntityManagerInterface $entityManager): Response
    {
        $sortie = $sortieRepository->find($id);
        if($sortie->getEtat()->getLibelle() == 'Créée')
        {
            $entityManager->remove($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie a été supprimée.');
        }
        else
        {
            $this->addFlash('danger', 'La sortie ne peut pas être supprimée.');
        }
        return $this->redirectToRoute('main_accueil');
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
        int $id,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
    ): Response
    {
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
        int $id,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher,
    ):Response
    {
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

    #[Route('/sorties/creation', name: 'sortie_creation', methods: ['GET','POST'])]
    public function creationSortie(Request $request,
                                   EntityManagerInterface $entityManager,
                                   EtatRepository $etatRepository): Response
    {

        /**
         * @var Participant $participant
         * */

        $nouvelSortie = new Sortie();
        $participant = $this->getUser();
        $nouvelSortie->setOrganisateur($participant);
        $nouvelSortie->setSiteOrganisateur($participant->getCampus());
        $nouvelSortie->setEtat($etatRepository->findOneBy(['libelle'=>'Créée']));
        $creationForm = $this->createForm(CreationSortieType::class, $nouvelSortie);
        $creationForm-> handleRequest($request);


        if ($creationForm->isSubmitted() && $creationForm->isValid()){

            $entityManager->persist($nouvelSortie);
            $entityManager->flush();

            $this->addFlash('success', 'votre sortie est enregistrée');
            return $this->redirectToRoute('main_accueil');
        }

        return $this->render('sortie/creation.html.twig',[
            'creationForm' => $creationForm->createView()
        ]);
    }

    #[Route('/sorties/creation/ville/{id}', name:'sortie_getLieux',  requirements: ['id' => '\d+'], methods: ["GET"])]
    public function getLieux(int $id, VilleRepository $villeRepository):Response
    {
        $ville = $villeRepository->find($id);
        $lieux = $ville->getLieux();
        return $this->json($lieux, Response::HTTP_OK, [], ['groups'=>'liste_lieu']);
    }

}
