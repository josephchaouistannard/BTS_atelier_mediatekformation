<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AdminCategoriesControllerTest extends WebTestCase
{
    private const RACINE = "/admin/categories";

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
        $this->assertSelectorTextContains('h5', 'Android');
    }

    public function testFiltreParNom()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE);

        // Soumettre formulaire avec bouton 'filtrer'
        $crawler = $client->submitForm('filtrer', ['recherche' => 'Cours']);

        // Vérifier nombre de resultats et premier resultat
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours');
    }

    public function testTriParNbFormations()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE . '/tri/nbFormations/ASC');

        // Vérifier premier resultat
        $this->assertSelectorTextContains('h5', "UML");

    }

    public function testClicLien()
    {
        $client = $this->initAdminClient();
        $client->request('GET', self::RACINE);

        // Clic sur lien (premier match)
        $client->clickLink('Formations');

        // Vérifier code réponse et URL
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/admin/formations', $uri);

        // Vérifier contenu
        $this->assertSelectorTextContains('h5', 'Eclipse n°8 : Déploiement');

    }
}
