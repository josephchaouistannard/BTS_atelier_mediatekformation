<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminplaylistsControllerTest extends WebTestCase
{
    private const RACINE = "/admin/playlists";

    public function initAdminClient()
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['username' => 'admin']);

        return $client->loginUser($user);
    }

    public function testPage()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTriParNom()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE . '/tri/name/ASC');

        // Vérifier premier resultat
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }

    public function testFiltreParNom()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE);

        // Soumettre formulaire avec bouton 'filtrer'
        $crawler = $client->submitForm('filtrer', ['recherche' => 'Cours UML']);

        // Vérifier nombre de resultats et premier resultat
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours UML');
    }

    public function testFiltreParCategorie()
    {
        $client = $this->initAdminClient();
        $crawler = $client->request('GET', self::RACINE);

        // Selectionner le formulaire
        $form = $crawler->filter('form')->eq(1)->form();

        // Selectionner le deuxième choix dans le combo (value = 2)
        $form['recherche'] = '2';
        $crawler = $client->submit($form);

        // Assert that the page contains only the filtered playlist
        $this->assertCount(2, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours UML');
    }

    public function testTriParNbFormations()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE . '/tri/nbFormations/ASC');

        // Vérifier premier resultat
        $this->assertSelectorTextContains('h5', "playlist test");

    }

    public function testClicLien()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE);

        // Clic sur lien (premier match)
        $client->clickLink('Ajouter une nouvelle playlist');

        // Vérifier code réponse et URL
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/admin/playlist/add', $uri);

        // Vérifier contenu
        $this->assertSelectorTextContains('h2', 'Ajouter une playlist :');

    }
}
