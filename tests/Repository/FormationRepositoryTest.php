<?php

namespace App\Tests\Repository;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase {

    private FormationRepository $repo;

    protected function setUp(): void {
        self::bootKernel();
        $this->repo = self::getContainer()->get(FormationRepository::class);
    }

    public function testFindAllOrderByTitleAsc(): void {
        $formations = $this->repo->findAllOrderBy('title', 'ASC');
        $this->assertGreaterThan(0, count($formations));
        $premier = $formations[0]->getTitle();
        $deuxieme = $formations[1]->getTitle();
        $this->assertTrue($premier <= $deuxieme);
    }

    public function testFindAllOrderByTitleDesc(): void {
        $formations = $this->repo->findAllOrderBy('title', 'DESC');
        $this->assertGreaterThan(0, count($formations));
        $premier = $formations[0]->getTitle();
        $deuxieme = $formations[1]->getTitle();
        $this->assertTrue($premier >= $deuxieme);
    }

    public function testFindByContainValue(): void {
        $formations = $this->repo->findByContainValue('title', 'Eclipse');
        $this->assertGreaterThan(0, count($formations));
        foreach($formations as $f){
            $this->assertStringContainsString('Eclipse', $f->getTitle());
        }
    }

    public function testFindAllLasted(): void {
        $formations = $this->repo->findAllLasted(3);
        $this->assertEquals(3, count($formations));
    }
}
