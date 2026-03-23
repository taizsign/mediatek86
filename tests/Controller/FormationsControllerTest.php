<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationsControllerTest extends WebTestCase {

    public function testPageFormationsAccessible(): void {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    public function testTriParTitreAsc(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertResponseIsSuccessful();
        $premiereLigne = $crawler->filter('tbody tr')->first()->filter('td')->first()->text();
        $this->assertEquals('Android Studio (complément n°1) : Navigation Drawer et Fragment', $premiereLigne);
    }

    public function testFiltreParTitre(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/recherche/title', ['recherche' => 'Eclipse']);
        $this->assertResponseIsSuccessful();
        $nbLignes = $crawler->filter('tbody tr')->count();
        $this->assertGreaterThan(0, $nbLignes);
        $premiereLigne = $crawler->filter('tbody tr')->first()->filter('td')->first()->text();
        $this->assertStringContainsString('Eclipse', $premiereLigne);
    }

    public function testClicVoirDetail(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations');
        $lien = $crawler->filter('tbody tr a')->first()->link();
        $client->click($lien);
        $this->assertResponseIsSuccessful();
    }
}
