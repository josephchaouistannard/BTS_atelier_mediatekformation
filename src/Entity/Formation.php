<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Représente une formation proposée par MediatekFormation.
 *
 * Une formation est caractérisée par une date de publication, un titre, une description,
 * un identifiant vidéo YouTube, une playlist associée et une ou plusieurs catégories.
 */
#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{

    /**
     * Début de chemin vers les images des vidéos YouTube.
     */
    private const CHEMIN_IMAGE = "https://i.ytimg.com/vi/";
        
    /**
     * L'identifiant unique de la formation.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * La date de publication de la formation.
     *
     * Ce champ est obligatoire et doit être une date valide.
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Assert\Range(
        min: '1900-01-01',
        max: 'now',
        notInRangeMessage: 'La date doit être entre {{ min }} et {{ max }}.'
    )]
    #[Assert\NotBlank(message: "La saisie d'une date est obligatoire.")]
    private ?\DateTimeInterface $publishedAt = null;

    /**
     * Le titre de la formation.
     *
     * Ce champ est obligatoire et ne peut pas dépasser 100 caractères.
     */
    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(max: 100, maxMessage: "Le titre ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\NotBlank(message: "La saisie d'un titre est obligatoire.")]
    private ?string $title = null;

    /**
     * La description détaillée de la formation.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * L'identifiant de la vidéo YouTube associée à la formation.
     *
     * Ce champ est obligatoire et ne peut pas dépasser 20 caractères.
     */
    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: "Le video ID ne peut pas dépasser {{ limit }} caractères.")]
    #[Assert\NotBlank(message: "La saisie d'un video ID est obligatoire.")]
    private ?string $videoId = null;

    /**
     * La playlist à laquelle cette formation est associée.
     *
     * Ce champ est obligatoire.
     */
    #[ORM\ManyToOne(inversedBy: 'formations')]
    #[Assert\NotBlank(message: "Le choix d'une playlist est obligatoire.")]
    private ?Playlist $playlist = null;

    /**
     * La collection des catégories associées à cette formation.
     *
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'formations')]
    private Collection $categories;

    /**
     * Constructeur de la classe Formation.
     *
     * Initialise la collection de catégories.
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant de la formation.
     *
     * @return int|null L'identifiant de la formation.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date de publication de la formation.
     *
     * @return \DateTimeInterface|null La date de publication.
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * Définit la date de publication de la formation.
     *
     * @param \DateTimeInterface|null $publishedAt La nouvelle date de publication.
     * @return static L'instance actuelle de la formation.
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * Retourne la date de publication formatée en chaîne de caractères (JJ/MM/AAAA).
     *
     * @return string La date de publication formatée, ou une chaîne vide si la date est nulle.
     */
    public function getPublishedAtString(): string {
        if($this->publishedAt == null){
            return "";
        }
        return $this->publishedAt->format('d/m/Y');
    }
    
    /**
     * Retourne le titre de la formation.
     *
     * @return string|null Le titre de la formation.
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Définit le titre de la formation.
     *
     * @param string|null $title Le nouveau titre de la formation.
     * @return static L'instance actuelle de la formation.
     */
    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Retourne la description de la formation.
     *
     * @return string|null La description de la formation.
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Définit la description de la formation.
     *
     * @param string|null $description La nouvelle description de la formation.
     * @return static L'instance actuelle de la formation.
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Retourne l'identifiant de la vidéo YouTube.
     *
     * @return string|null L'identifiant de la vidéo.
     */
    public function getVideoId(): ?string
    {
        return $this->videoId;
    }

    /**
     * Définit l'identifiant de la vidéo YouTube.
     *
     * @param string|null $videoId Le nouvel identifiant de la vidéo.
     * @return static L'instance actuelle de la formation.
     */
    public function setVideoId(?string $videoId): static
    {
        $this->videoId = $videoId;

        return $this;
    }

    /**
     * Retourne l'URL de la miniature par défaut de la vidéo YouTube.
     *
     * @return string|null L'URL de la miniature.
     */
    public function getMiniature(): ?string
    {
        return self::CHEMIN_IMAGE.$this->videoId."/default.jpg";
    }

    /**
     * Retourne l'URL de l'image de haute qualité de la vidéo YouTube.
     *
     * @return string|null L'URL de l'image.
     */
    public function getPicture(): ?string
    {
        return self::CHEMIN_IMAGE.$this->videoId."/hqdefault.jpg";
    }
    
    /**
     * Retourne la playlist associée à la formation.
     *
     * @return Playlist|null La playlist.
     */
    public function getPlaylist(): ?playlist
    {
        return $this->playlist;
    }

    /**
     * Définit la playlist associée à la formation.
     *
     * @param Playlist|null $playlist La nouvelle playlist.
     * @return static L'instance actuelle de la formation.
     */
    public function setPlaylist(?Playlist $playlist): static
    {
        $this->playlist = $playlist;

        return $this;
    }

    /**
     * Retourne la collection des catégories associées à cette formation.
     *
     * @return Collection<int, Categorie> La collection de catégories.
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    /**
     * Ajoute une catégorie à cette formation.
     *
     * Si la catégorie n'est pas déjà associée, elle est ajoutée.
     *
     * @param Categorie $category La catégorie à ajouter.
     * @return static L'instance actuelle de la formation.
     */
    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * Supprime une catégorie de cette formation.
     *
     * Si la catégorie est associée, elle est retirée.
     *
     * @param Categorie $category La catégorie à supprimer.
     * @return static L'instance actuelle de la formation.
     */
    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }
}
