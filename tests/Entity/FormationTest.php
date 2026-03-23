<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use PHPUnit\Framework\TestCase;

class FormationTest extends TestCase {

    public function testGetPublishedAtString(): void {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2020-01-04 17:00:12'));
        $this->assertEquals('04/01/2020', $formation->getPublishedAtString());
    }

    public function testGetPublishedAtStringVide(): void {
        $formation = new Formation();
        $this->assertEquals('', $formation->getPublishedAtString());
    }
}
