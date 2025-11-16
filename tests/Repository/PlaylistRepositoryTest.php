<?php

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{

    public function getRepository(): PlaylistRepository
    {
        self::bootKernel();
        return self::getContainer()->get(PlaylistRepository::class);
    }

    public function newPlaylist(): Playlist
    {
        return (new Playlist())
            ->setName('Test Playlist')
            ->setDescription('Test description');
    }

    public function testNbPlaylists()
    {
        $repository = $this->getRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(28, $nbPlaylists);
    }

    public function testAddPlaylist()
    {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "Erreur lors de l'ajout");
    }

    public function testRemovePlaylist()
    {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "Erreur lors de la suppression");
    }

    public function testFindAllOrderByName() {
        $repository = $this->getRepository();
        $playlists = $repository->findAllOrderByName('ASC');
        $this->assertEquals($repository->count([]), count($playlists));

        // Extraire les noms
        $names = [];
        foreach ($playlists as $playlist) {
            $names[] = $playlist->getName();
        }

        // Créer une version triée
        $sortedNames = $names;
        sort($sortedNames, SORT_STRING | SORT_FLAG_CASE);

        $this->assertEquals($sortedNames, $names);
    }

    public function testFindByContainValue() {
        $repository = $this->getRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist);

        $playlists = $repository->findByContainValue('name', 'Test Playlist');
        $this->assertEquals(1, count($playlists));

        $playlists = $repository->findByContainValue('name', 'Test Playlist Non Existant');
        $this->assertEquals(0, count($playlists));
    }

    public function testFindAllOrderByNbFormations() {
        $repository = $this->getRepository();
        $playlists = $repository->findAllOrderByNbFormations('ASC');
        $this->assertEquals($repository->count([]), count($playlists));

        for ($i = 0; $i < count($playlists) - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                count($playlists[$i]->getFormations()),
                count($playlists[$i + 1]->getFormations()),
                "Les playlists ne sont pas triées correctement"
            );
        }
    }
}
