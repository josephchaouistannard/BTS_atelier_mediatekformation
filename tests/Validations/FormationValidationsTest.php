<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use App\Entity\Playlist;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationsTest extends KernelTestCase
{

    public function getFormation() : Formation {
        return (new Formation())
            ->setTitle('Test Formation')
            ->setVideoId('vidtest')
            ->setPlaylist(new Playlist())
            ->setPublishedAt(new \DateTime("now"));
    }

    public function testNonValidPublishedAt() {
        // Initialise Symfony pour les tests et la classe de validation
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);

        // Get une instance de Formation
        $formation = $this->getFormation();

        // Ajouter 1 jour - demain, donc non valide
        $formation->setPublishedAt((new \DateTime())->modify('+1 day'));

        // Verifier qu'il y bien 1 erreur
        $errors = $validator->validate($formation);
        $this->assertCount(1, $errors);
    }
}
