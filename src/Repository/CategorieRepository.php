<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Dépôt pour l'entité Categorie.
 *
 * Fournit des méthodes pour interagir avec les objets Categorie dans la base de données.
 *
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    /**
     * Constructeur de la classe CategorieRepository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * Ajoute une nouvelle catégorie ou met à jour une catégorie existante.
     *
     * @param Categorie $entity L'entité Categorie à ajouter ou mettre à jour.
     * @return void
     */
    public function add(Categorie $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une catégorie de la base de données.
     *
     * @param Categorie $entity L'entité Categorie à supprimer.
     * @return void
     */
    public function remove(Categorie $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne la liste des catégories associées aux formations d'une playlist spécifique.
     * Les catégories sont triées par nom dans l'ordre ascendant.
     *
     * @param int $idPlaylist L'identifiant de la playlist.
     * @return Categorie[] Un tableau d'objets Categorie.
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
     * Retourne toutes les catégories triées par leur nom.
     *
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @return Categorie[] Un tableau d'objets Categorie.
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.name', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne toutes les catégories triées par le nombre de formations qu'elles contiennent.
     *
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @return Categorie[] Un tableau d'objets Categorie.
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
     * Recherche les catégories dont le nom contient une valeur spécifique.
     * Si la valeur est vide, toutes les catégories triées par nom ascendant sont retournées.
     *
     * @param string $valeur La valeur à rechercher dans le nom des catégories.
     * @return Categorie[] Un tableau d'objets Categorie correspondant à la recherche.
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
