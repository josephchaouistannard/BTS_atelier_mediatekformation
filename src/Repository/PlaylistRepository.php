<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Dépôt pour l'entité Playlist.
 *
 * Fournit des méthodes pour interagir avec les objets Playlist dans la base de données,
 * incluant des fonctionnalités de tri et de recherche.
 *
 * @extends ServiceEntityRepository<Playlist>
 */
class PlaylistRepository extends ServiceEntityRepository
{
    /**
     * Constructeur de la classe PlaylistRepository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Playlist::class);
    }

    /**
     * Ajoute une nouvelle playlist ou met à jour une playlist existante.
     *
     * @param Playlist $entity L'entité Playlist à ajouter ou mettre à jour.
     * @return void
     */
    public function add(Playlist $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une playlist de la base de données.
     *
     * @param Playlist $entity L'entité Playlist à supprimer.
     * @return void
     */
    public function remove(Playlist $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les playlists triées par leur nom.
     *
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @return Playlist[] Un tableau d'objets Playlist.
     */
    public function findAllOrderByName($ordre): array
    {
        return $this->createQueryBuilder('p')
            ->leftjoin('p.formations', 'f')
            ->groupBy('p.id')
            ->orderBy('p.name', $ordre)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche les playlists dont un champ contient une valeur spécifique.
     * Si la valeur est vide, toutes les playlists triées par nom ascendant sont retournées.
     *
     * @param string $champ Le champ sur lequel appliquer le filtre (ex: 'name', 'description').
     * @param string $valeur La valeur à rechercher.
     * @param string $table Le nom de la table si le champ de recherche se trouve dans une entité liée (ex: 'categories').
     * @return Playlist[] Un tableau d'objets Playlist correspondant à la recherche.
     */
    public function findByContainValue($champ, $valeur, $table = ""): array
    {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        if ($table == "") {
            return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->where('p.' . $champ . ' LIKE :valeur')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->groupBy('p.id')
                ->orderBy('p.name', 'ASC')
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('p')
                ->leftjoin('p.formations', 'f')
                ->leftjoin('f.categories', 'c')
                ->where('c.' . $champ . ' LIKE :valeur')
                ->setParameter('valeur', '%' . $valeur . '%')
                ->groupBy('p.id')
                ->orderBy('p.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * Retourne toutes les playlists triées par le nombre de formations qu'elles contiennent.
     *
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @return Playlist[] Un tableau d'objets Playlist.
     */
    public function findAllOrderByNbFormations($ordre): array
    {
        return $this->createQueryBuilder('p')
            ->leftjoin('p.formations', 'f')
            ->addSelect('COUNT(f.id) AS HIDDEN formationsCount')
            ->groupBy('p.id')
            ->orderBy('formationsCount', $ordre)
            ->getQuery()
            ->getResult();
    }

}
