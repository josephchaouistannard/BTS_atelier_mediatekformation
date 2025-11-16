<?php

namespace App\Entity;

use App\Repository\PlaylistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Représente une playlist de formations.
 *
 * Une playlist regroupe plusieurs formations et est caractérisée par un nom et une description.
 */
#[ORM\Entity(repositoryClass: PlaylistRepository::class)]
class Playlist
{
    /**
     * L'identifiant unique de la playlist.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom de la playlist.
     *
     * Ce champ est obligatoire et ne peut pas dépasser 100 caractères.
     */
    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: "Le name ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\NotBlank(message: "La saisie de name est obligatoire.")]
    private ?string $name = null;

    /**
     * La description détaillée de la playlist.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * La collection des formations associées à cette playlist.
     *
     * @var Collection<int, Formation>
     */
    #[ORM\OneToMany(targetEntity: Formation::class, mappedBy: 'playlist')]
    private Collection $formations;

    /**
     * Constructeur de la classe Playlist.
     *
     * Initialise la collection de formations.
     */
    public function __construct()
    {
        $this->formations = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant de la playlist.
     *
     * @return int|null L'identifiant de la playlist.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom de la playlist.
     *
     * @return string|null Le nom de la playlist.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom de la playlist.
     *
     * @param string|null $name Le nouveau nom de la playlist.
     * @return static L'instance actuelle de la playlist.
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne la description de la playlist.
     *
     * @return string|null La description de la playlist.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la playlist.
     *
     * @param string|null $description La nouvelle description de la playlist.
     * @return static L'instance actuelle de la playlist.
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne la collection des formations associées à cette playlist.
     *
     * @return Collection<int, Formation> La collection de formations.
     */
    public function getFormations(): Collection
    {
        return $this->formations;
    }

    /**
     * Ajoute une formation à cette playlist.
     *
     * Si la formation n'est pas déjà associée, elle est ajoutée et la relation inverse est établie.
     *
     * @param Formation $formation La formation à ajouter.
     * @return static L'instance actuelle de la playlist.
     */
    public function addFormation(Formation $formation): static
    {
        if (!$this->formations->contains($formation)) {
            $this->formations->add($formation);
            $formation->setPlaylist($this);
        }

        return $this;
    }

    /**
     * Supprime une formation de cette playlist.
     *
     * Si la formation est associée et que cette playlist est sa playlist parente,
     * elle est retirée et la relation inverse est supprimée.
     *
     * @param Formation $formation La formation à supprimer.
     * @return static L'instance actuelle de la playlist.
     */
    public function removeFormation(Formation $formation): static
    {
        if ($this->formations->removeElement($formation) && $formation->getPlaylist() === $this) {
            // set the owning side to null (unless already changed)
            $formation->setPlaylist(null);
        }

        return $this;
    }
    
    /**
     * Retourne une collection des noms de catégories uniques associées aux formations de cette playlist.
     *
     * @return Collection<int, string> Une collection de noms de catégories.
     */
    public function getCategoriesPlaylist() : Collection
    {
        $categories = new ArrayCollection();
        foreach($this->formations as $formation){
            $categoriesFormation = $formation->getCategories();
            foreach ($categoriesFormation as $categorieFormation) {
                if (!$categories->contains($categorieFormation->getName())) {
                    $categories[] = $categorieFormation->getName();
                }
            }
        }
        return $categories;
    }
        
}
