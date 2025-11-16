<?php

namespace App\Repository;

use App\Entity\Formation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Dépôt pour l'entité Formation.
 *
 * Fournit des méthodes pour interagir avec les objets Formation dans la base de données,
 * incluant des fonctionnalités de tri, de recherche et de récupération des formations.
 *
 * @extends ServiceEntityRepository<Formation>
 */
class FormationRepository extends ServiceEntityRepository
{
    /**
     * Constructeur de la classe FormationRepository.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire d'entités.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formation::class);
    }

    /**
     * Ajoute une nouvelle formation ou met à jour une formation existante.
     *
     * @param Formation $entity L'entité Formation à ajouter ou mettre à jour.
     * @return void
     */
    public function add(Formation $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Supprime une formation de la base de données.
     *
     * @param Formation $entity L'entité Formation à supprimer.
     * @return void
     */
    public function remove(Formation $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }

    /**
     * Retourne toutes les formations triées sur un champ donné.
     *
     * @param string $champ Le champ sur lequel trier (ex: 'title', 'publishedAt').
     * @param string $ordre L'ordre de tri ('ASC' pour ascendant, 'DESC' pour descendant).
     * @param string $table Le nom de la table si le champ de tri se trouve dans une entité liée (ex: 'playlist', 'categories').
     * @return Formation[] Un tableau d'objets Formation.
     */
    public function findAllOrderBy($champ, $ordre, $table=""): array{
        if($table==""){
            return $this->createQueryBuilder('f')
                    ->orderBy('f.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->orderBy('t.'.$champ, $ordre)
                    ->getQuery()
                    ->getResult();
        }
    }

    /**
     * Recherche les formations dont un champ contient une valeur spécifique.
     * Si la valeur est vide, toutes les formations sont retournées.
     * Les résultats sont triés par date de publication descendante.
     *
     * @param string $champ Le champ sur lequel appliquer le filtre (ex: 'title', 'description').
     * @param string $valeur La valeur à rechercher.
     * @param string $table Le nom de la table si le champ de recherche se trouve dans une entité liée (ex: 'playlist', 'categories').
     * @return Formation[] Un tableau d'objets Formation correspondant à la recherche.
     */
    public function findByContainValue($champ, $valeur, $table=""): array{
        if($valeur==""){
            return $this->findAll();
        }
        if($table==""){
            return $this->createQueryBuilder('f')
                    ->where('f.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();
        }else{
            return $this->createQueryBuilder('f')
                    ->join('f.'.$table, 't')
                    ->where('t.'.$champ.' LIKE :valeur')
                    ->orderBy('f.publishedAt', 'DESC')
                    ->setParameter('valeur', '%'.$valeur.'%')
                    ->getQuery()
                    ->getResult();
        }
    }
    
    /**
     * Retourne les 'n' formations les plus récentes.
     *
     * @param int $nb Le nombre de formations les plus récentes à retourner.
     * @return Formation[] Un tableau d'objets Formation.
     */
    public function findAllLasted($nb) : array {
        return $this->createQueryBuilder('f')
                ->orderBy('f.publishedAt', 'DESC')
                ->setMaxResults($nb)
                ->getQuery()
                ->getResult();
    }
    
    /**
     * Retourne la liste des formations associées à une playlist spécifique.
     * Les formations sont triées par date de publication ascendante.
     *
     * @param int $idPlaylist L'identifiant de la playlist.
     * @return Formation[] Un tableau d'objets Formation.
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('f.publishedAt', 'ASC')
                ->getQuery()
                ->getResult();
    }
    
}
