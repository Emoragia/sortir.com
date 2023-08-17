<?php

namespace App\Repository;

use App\Data\SortieRechercheData;
use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @return Sortie[] Returns an array of Sortie objects
     */
    public function findSorties(SortieRechercheData $data): array
    {
//        $participant = $data->participant;
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->innerJoin('s.etat', 'e')
            ->addSelect('e')
            ->leftJoin('s.participants', 'p')
            ->addSelect('p');
        $queryBuilder->andWhere('s.siteOrganisateur = :campus');
        $queryBuilder->groupBy('s.idSortie');

        //Sélection par campus
        if(is_null($data->campus))
        {
            $queryBuilder->setParameter('campus', $data->participant->getCampus()->getId());
        }
        else
        {
            $queryBuilder->setParameter('campus', $data->campus);
        }

        //Sélection par nom:
        if(!is_null($data->nomRecherche))
        {
            $queryBuilder->andWhere('s.nom LIKE :mot')
                ->setParameter('mot', $data->nomRecherche);
        }

        //Sélection par date
        if(!is_null($data->borneDateInf) && !is_null($data->borneDateSup))
        {
            $queryBuilder->andWhere('s.dateHeureDebut BETWEEN :borneMin AND :borneMax')
                ->setParameter('borneMin', $data->borneDateInf)
                ->setParameter('borneMax', $data->borneDateSup);
        }
        //selection selon organisateur.ice
        if($data->organisateur)
        {
            $queryBuilder->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $data->participant);
        }

        //selection selon inscription
        if($data->inscrit && !$data->nonInscrit)
        {
            $queryBuilder->andWhere('s.participants = :participant')
                ->setParameter('participant', $data->participant);
        }

        if(!$data->inscrit && $data->nonInscrit)
        {
            $queryBuilder->andWhere('s.participants != :participant')
                ->setParameter('participant', $data->participant);

        }

        //selection des sorties par etat
        if(!$data->sortiesPassees)
        {
            $queryBuilder->andWhere('s.etat IN (1,2,3)');
        }

        //TODO : ajouter que GETDATE() - s.dateHeureDebut <= 1 mois
        else
        {
            $queryBuilder->andWhere('s.etat = 4');
        }


        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
