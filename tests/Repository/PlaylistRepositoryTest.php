<?php

namespace App\Tests\Repository;

use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase {

    private PlaylistRepository $repo;

    protected function setUp(): void {
        self::bootKernel();
        $this->repo = self::getContainer()->get(PlaylistRepository::class);
    }

    public function testFindAllOrderByFormationsCountAsc(): void {
        $playlists = $this->repo->findAllOrderByFormationsCount('ASC');
        $this->assertGreaterThan(0, count($playlists));
        $premier = count($playlists[0]->getFormations());
        $deuxieme = count($playlists[1]->getFormations());
        $this->assertLessThanOrEqual($deuxieme, $premier);
    }

    public function testFindAllOrderByFormationsCountDesc(): void {
        $playlists = $this->repo->findAllOrderByFormationsCount('DESC');
        $this->assertGreaterThan(0, count($playlists));
        $premier = count($playlists[0]->getFormations());
        $deuxieme = count($playlists[1]->getFormations());
        $this->assertGreaterThanOrEqual($deuxieme, $premier);
    }

    public function testFindByContainValue(): void {
        $playlists = $this->repo->findByContainValue('name', 'Cours');
        $this->assertGreaterThan(0, count($playlists));
        foreach($playlists as $p){
            $this->assertStringContainsString('Cours', $p->getName());
        }
    }
}
