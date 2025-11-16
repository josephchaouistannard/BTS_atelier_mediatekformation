<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

/**
 * Fixture pour charger des données d'utilisateurs dans la base de données.
 *
 * Cette classe est utilisée pour créer un utilisateur administrateur par défaut
 * lors du chargement des fixtures.
 */
class UserFixture extends Fixture
{
    /**
     * Le service de hachage de mot de passe.
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * Constructeur de la classe UserFixture.
     *
     * Injecte le service UserPasswordHasherInterface pour hacher les mots de passe.
     *
     * @param UserPasswordHasherInterface $passwordHasher Le service de hachage de mot de passe.
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Charge les données d'utilisateurs dans la base de données.
     *
     * Crée un utilisateur avec le nom d'utilisateur "admin", hache son mot de passe
     * et lui attribue le rôle 'ROLE_ADMIN'.
     *
     * @param ObjectManager $manager Le gestionnaire d'objets Doctrine.
     * @return void
     */
    public function load(ObjectManager $manager): void
    {

        $user = new User();
        $user->setUsername("admin");
        $plaintextPassword = "admin";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

    }
}
