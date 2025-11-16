<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Représente un utilisateur de l'application.
 *
 * Cette entité implémente UserInterface et PasswordAuthenticatedUserInterface
 * pour la gestion de l'authentification Symfony.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * L'identifiant unique de l'utilisateur.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Le nom d'utilisateur (username) de l'utilisateur.
     *
     * Ce champ doit être unique.
     */
    #[ORM\Column(length: 180)]
    private ?string $username = null;

    /**
     * Les rôles attribués à l'utilisateur.
     *
     * @var list<string> Les rôles de l'utilisateur.
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Le mot de passe haché de l'utilisateur.
     *
     * @var string Le mot de passe haché.
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Retourne l'identifiant de l'utilisateur.
     *
     * @return int|null L'identifiant de l'utilisateur.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom d'utilisateur de l'utilisateur.
     *
     * @return string|null Le nom d'utilisateur.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Définit le nom d'utilisateur de l'utilisateur.
     *
     * @param string $username Le nouveau nom d'utilisateur.
     * @return static L'instance actuelle de l'utilisateur.
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Retourne l'identifiant visuel qui représente cet utilisateur.
     *
     * @see UserInterface
     * @return string L'identifiant de l'utilisateur (son nom d'utilisateur).
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Retourne les rôles de l'utilisateur.
     *
     * Garantit que chaque utilisateur a au moins le rôle 'ROLE_USER'.
     *
     * @see UserInterface
     * @return list<string> Les rôles de l'utilisateur.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles Les nouveaux rôles de l'utilisateur.
     * @return static L'instance actuelle de l'utilisateur.
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Retourne le mot de passe haché de l'utilisateur.
     *
     * @see PasswordAuthenticatedUserInterface
     * @return string Le mot de passe haché.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe de l'utilisateur.
     *
     * @param string $password Le nouveau mot de passe haché.
     * @return static L'instance actuelle de l'utilisateur.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Efface les informations sensibles temporaires de l'utilisateur.
     *
     * Cette méthode est appelée après l'authentification pour nettoyer les données
     * qui ne doivent pas être stockées de manière persistante (ex: mot de passe en clair).
     *
     * @see UserInterface
     * @return void
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
