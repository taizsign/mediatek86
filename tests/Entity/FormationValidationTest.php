<?php

namespace App\Tests\Entity;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationValidationTest extends KernelTestCase {

    public function testDateFuturRefusee(): void {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2030-01-01'));
        $errors = $validator->validate($formation);
        $this->assertGreaterThan(0, count($errors));
    }

    public function testDatePasseeAcceptee(): void {
        self::bootKernel();
        $validator = self::getContainer()->get('validator');
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2020-01-01'));
        $errors = $validator->validate($formation);
        $this->assertEquals(0, count($errors));
    }
}
