<?php

namespace AdressBookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailControllerTest extends WebTestCase
{
    public function testAddnewemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/addNewEmail');
    }

    public function testCreatenewemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/createNewEmail');
    }

}
