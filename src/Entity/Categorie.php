<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Représente une catégorie pour organiser les formations.
 *
 * Une catégorie peut être associée à plusieurs formations.
 * Le nom de la catégorie doit être unique.
 */
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'La catégorie doit être unique.')]
class Categorie
{
    /**
     * L'identifiant unique de la catégorie.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom de la catégorie.
     *
     * Ce champ est obligatoire et ne peut pas dépasser 50 caractères.
     */
    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50, maxMessage: "Le name ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\NotBlank(message: "Le nom de la catégorie ne peut pas être vide.")]
    private ?string $name = null;

    /**
     * La collection des formations associées à cette catégorie.
     *
     * @var Collection<int, Formation>
     */
    #[ORM\ManyToMany(targetEntity: Formation::class, mappedBy: 'categories')]
    private Collection $formations;

    /**
     * Constructeur de la classe Categorie.
     *
     * Initialise la collection de formations.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant de la catégorie.
     *
     * @return int|null L'identifiant de la catégorie.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la catégorie.
     *
     * @return string|null Le nom de la catégorie.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom de la catégorie.
     *
     * @param string|null $name Le nouveau nom de la catégorie.
     * @return static L'instance actuelle de la catégorie.
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne la collection des formations associées à cette catégorie.
     *
     * @return Collection<int, Formation> La collection de formations.
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Ajoute une formation à cette catégorie.
     *
     * Si la formation n'est pas déjà associée, elle est ajoutée et la relation inverse est établie.
     *
     * @param Formation $formation La formation à ajouter.
     * @return static L'instance actuelle de la catégorie.
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->addCategory($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de cette catégorie.
     *
     * Si la formation est associée, elle est retirée et la relation inverse est supprimée.
     *
     * @param Formation $formation La formation à supprimer.
     * @return static L'instance actuelle de la catégorie.
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation)) {
            $formation->removeCategory($this);
        }

        return $this;
    }
}
