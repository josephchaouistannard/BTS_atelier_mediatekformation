<?php

namespace App\Tests;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase {

    public function testGetPublishedAtString() {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime("2021-01-21"));
        $this->assertEquals("21/01/2021", $formation->getPublishedAtString());
    }
}
