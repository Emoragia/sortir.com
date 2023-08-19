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

        $queryBuilder->groupBy('s.idSortie');
        $queryBuilder->orderBy('s.dateHeureDebut', 'DESC');

        //Sélection par campus
        if($data->campus)
        {
            $queryBuilder->andWhere('s.siteOrganisateur = :campus');
            $queryBuilder->setParameter('campus', $data->campus);
        }

        //Sélection par nom:
        if($data->nomRecherche)
        {
            $queryBuilder->andWhere('s.nom LIKE :mot')
                ->setParameter('mot', $data->nomRecherche);
        }

        //Sélection par date
        if(!$data->borneDateInf && $data->borneDateSup)
        {
            $queryBuilder->andWhere('s.dateHeureDebut BETWEEN :borneMin AND :borneMax')
                ->setParameter('borneMin', $data->borneDateInf)
                ->setParameter('borneMax', $data->borneDateSup);
        }
        //selection selon organisateur.ice
        if($data->organisateur) {

            $queryBuilder
                ->orWhere('e.libelle = \'Créée\'')
                ->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $data->participant);

        }
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
        if($data->sortiesPassees)
        {
            $queryBuilder->andWhere('e.libelle = \'Passée\' AND DATE_DIFF(CURRENT_DATE(), s.dateHeureDebut) <= 31');
        }

        //TODO : ajouter que GETDATE() - s.dateHeureDebut <= 1 mois
        else
        {
            if($data->organisateur)
            {
                $queryBuilder->andWhere('e.libelle IN (\'Ouverte\', \'En cours\', \'Créée\')');
            }
            else
            {
                $queryBuilder->andWhere('e.libelle IN (\'Ouverte\', \'En cours\')');
            }

        }


        $query = $queryBuilder->getQuery();
        return $query->getResult();
    }

}
