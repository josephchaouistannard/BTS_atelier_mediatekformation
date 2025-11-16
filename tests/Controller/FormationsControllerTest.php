<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FormationsControllerTest extends WebTestCase
{
    private const RACINE = "/formations";

    public function testPage()
    {
        $client = static::createClient();
        $client->request('GET', self::RACINE);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testTriParNom()
    {
        $client = static::createClient();
        $client->request('GET', self::RACINE . '/tri/title/ASC');

        // Vérifier premier resultat
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }

    public function testFiltreParNom()
    {
        $client = static::createClient();
        $client->request('GET', self::RACINE);

        // Soumettre formulaire avec bouton 'filtrer'
        $crawler = $client->submitForm('filtrer', ['recherche' => 'UML : Diagramme de paquetages']);

        // Vérifier nombre de resultats et premier resultat
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }

    public function testTriParPlaylist()
    {
        $client = static::createClient();
        $client->request('GET', self::RACINE . '/tri/name/ASC/playlist');

        // Vérifier premier resultat
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');
    }

    public function testFiltreParPlaylist()
    {
        $client = static::createClient();
        $client->request('GET', self::RACINE);

        // Soumettre formulaire avec bouton 'filtrer'
        $crawler = $client->submitForm('filtrer', ['recherche' => 'uml']);

        // Vérifier nombre de resultats et premier resultat
        $this->assertCount(10, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'UML : Diagramme de paquetages');
    }

    public function testFiltreParCategorie()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::RACINE);

        // Selectionner le formulaire
        $form = $crawler->filter('form')->eq(2)->form();

        // Selectionner le deuxième choix dans le combo (value = 2)
        $form['recherche'] = '2';
        $crawler = $client->submit($form);

        // Assert that the page contains only the filtered playlist
        $this->assertCount(11, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°2 : rétroconception avec ObjectAid');
    }

    public function testTriParDate()
    {
        $client = static::createClient();
        $client->request('GET', self::RACINE . '/tri/publishedAt/ASC');

        // Vérifier premier resultat
        $this->assertSelectorTextContains('h5', "Cours UML (1 à 7 / 33) : introduction et cas d'utilisation");

    }

    public function testClicLien()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', self::RACINE);

        // Clic sur lien (premier match)
        // Selectionner le premier lien qui contient un élément img
        $link = $crawler->filterXPath('//a[img]')->first()->link();
        $client->click($link);

        // Vérifier code réponse et URL
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);

        // Vérifier contenu
        $this->assertSelectorTextContains('h4', 'Eclipse n°8 : Déploiement');

    }
}
