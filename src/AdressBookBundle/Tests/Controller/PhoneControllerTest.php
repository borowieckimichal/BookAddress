<?php

namespace AdressBookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PhoneControllerTest extends WebTestCase
{
    public function testAddnewphone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addNewPhone');
    }

    public function testCreatenewphone()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createNewPhone');
    }

}
