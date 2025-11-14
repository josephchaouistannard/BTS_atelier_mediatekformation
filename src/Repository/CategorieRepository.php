<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param int $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')
                ->getQuery()
                ->getResult();
    }

    /**
     * Retourne toutes les catégories triées sur leur nom
     * @param string $ordre
     * @return Categorie[]
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne toutes les catégories triées sur le nb de formations dans la catégorie
     * @param string $champ
     * @param string $ordre
     * @return Categorie[]
     */
    public function findAllOrderByNbFormations($ordre): array
    {
        return $this->createQueryBuilder('c')
            ->leftjoin('c.formations', 'f')
            ->addSelect('COUNT(f.id) AS HIDDEN formationsCount')
            ->groupBy('c.id')
            ->orderBy('formationsCount', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Enregistrements dont un le champ name contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param string $valeur
     * @return Categorie[]
     */
    public function findByContainValue($valeur): array
    {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        else {
            return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->where('p.name'. ' LIKE :valeur')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->groupBy('p.id')
                ->orderBy('p.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }
}
