<?php

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieRepositoryTest extends KernelTestCase
{

    public function getRepository(): CategorieRepository
    {
        self::bootKernel();
        return self::getContainer()->get(CategorieRepository::class);
    }

    public function newCategorie(): Categorie
    {
        return (new Categorie())
            ->setName('Test Categorie');
    }

    public function testNbCategories()
    {
        $repository = $this->getRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }

    public function testAddCategorie()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    public function testRemoveFormation()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "Erreur lors de la suppression");
    }

    public function testFindAllForOnePlaylist()
    {
        $repository = $this->getRepository();
        $categories = $repository->findAllForOnePlaylist(1);

        $this->assertEquals(2, count($categories));
    }

    public function testFindAllOrderByName()
    {
        $repository = $this->getRepository();
        $categories = $repository->findAllOrderByName('ASC');
        $this->assertEquals($repository->count([]), count($categories));

        // Extraire les noms
        $names = [];
        foreach ($categories as $categorie) {
            $names[] = $categorie->getName();
        }

        // Créer une version triée
        $sortedNames = $names;
        sort($sortedNames, SORT_STRING | SORT_FLAG_CASE);

        $this->assertEquals($sortedNames, $names);
    }


    public function testFindAllByNbFormations()
    {
        $repository = $this->getRepository();
        $categories = $repository->findAllOrderByNbFormations('ASC');
        $this->assertEquals($repository->count([]), count($categories));

        for ($i = 0; $i < count($categories) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                count($categories[$i]->getFormations()),
                count($categories[$i + 1]->getFormations()),
                "Les catégories ne sont pas triées correctement"
            );
        }
    }

    public function testFindByContainValue()
    {
        $repository = $this->getRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie);

        $categories = $repository->findByContainValue('Test Categorie');
        $this->assertEquals(1, count($categories));

        $categories = $repository->findByContainValue('Test Categorie Non Existant');
        $this->assertEquals(0, count($categories));
    }
}
