<?php

namespace AdressBookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdressControllerTest extends WebTestCase
{
    public function testAddnewadress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addNewAdress');
    }

    public function testCreatenewadress()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createNewAdress');
    }

}
