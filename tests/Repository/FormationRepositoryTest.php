<?php

namespace App\Tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{

    public function getRepository(): FormationRepository
    {
        self::bootKernel();
        return self::getContainer()->get(FormationRepository::class);
    }

    public function newFormation(): Formation
    {
        $playlistRepository = self::getContainer()->get(PlaylistRepository::class);
        $playlist = $playlistRepository->findOneBy([]);
        $formation = (new Formation())
            ->setTitle('Test Formation')
            ->setPublishedAt(new \DateTime('now'))
            ->setDescription('Test description')
            ->setVideoId('vidtest')
            ->setPlaylist($playlist);
        return $formation;

    }

    public function testNbFormations()
    {
        $repository = $this->getRepository();
        $nbFormations = $repository->count([]);
        $this->assertEquals(237, $nbFormations);
    }

    public function testAddFormation()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $nbFormations = $repository->count([]);
        $repository->add($formation);
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    public function testRemoveFormation()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation);
        $nbFormations = $repository->count([]);
        $repository->remove($formation);
        $this->assertEquals($nbFormations - 1, $repository->count([]), "Erreur lors de la suppression");
    }

    public function testFindAllOrderBy()
    {
        $repository = $this->getRepository();

        $formations = $repository->findAllOrderBy('publishedAt', 'DESC');
        // Contrôler que la méthode récupère toutes les formations
        $this->assertEquals($repository->count([]), count($formations));

        // Et qu'elles sont triées correctement
        for ($i = 0; $i < 20; $i++) {
            $this->assertGreaterThanOrEqual(
                $formations[$i + 1]->getPublishedAt(),
                $formations[$i]->getPublishedAt(),
                "Les formations ne sont pas triées par date"
            );
        }
    }

    public function testFindByContainValue()
    {
        $repository = $this->getRepository();
        $formation = $this->newFormation();
        $repository->add($formation);

        $formations = $repository->findByContainValue('title', 'Test Formation');
        $this->assertEquals(1, count($formations));

        $formations = $repository->findByContainValue('title', 'Test Formation Non Existant');
        $this->assertEquals(0, count($formations));

    }

    public function testFindAllLasted()
    {
        $repository = $this->getRepository();

        $formations = $repository->findAllLasted(21);
        // Contrôler que la méthode récupère 21 formations
        $this->assertEquals(21, count($formations));

        // Et qu'elles sont triées correctement
        for ($i = 0; $i < 20; $i++) {
            $this->assertGreaterThanOrEqual(
                $formations[$i + 1]->getPublishedAt(),
                $formations[$i]->getPublishedAt(),
                "Les formations ne sont pas triées par date"
            );
        }
    }

    public function testFindAllForOnePlaylist()
    {
        $repository = $this->getRepository();
        $formations = $repository->findAllForOnePlaylist(1);

        $this->assertEquals(8, count($formations));
    }
}
