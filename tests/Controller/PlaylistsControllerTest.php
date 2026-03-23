<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlaylistsControllerTest extends WebTestCase {

    public function testPagePlaylistsAccessible(): void {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    public function testTriParNomAsc(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertResponseIsSuccessful();
        $premiereLigne = $crawler->filter('tbody tr')->first()->filter('td')->first()->text();
        $this->assertEquals('Bases de la programmation (C#)', $premiereLigne);
    }

    public function testFiltreParNom(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/recherche/name', ['recherche' => 'Cours']);
        $this->assertResponseIsSuccessful();
        $nbLignes = $crawler->filter('tbody tr')->count();
        $this->assertGreaterThan(0, $nbLignes);
        $premiereLigne = $crawler->filter('tbody tr')->first()->filter('td')->first()->text();
        $this->assertStringContainsString('Cours', $premiereLigne);
    }

    public function testClicVoirDetail(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');
        $lien = $crawler->filter('tbody tr')->first()->selectLink('Voir détail')->link();
        $client->click($lien);
        $this->assertResponseIsSuccessful();
    }
}
