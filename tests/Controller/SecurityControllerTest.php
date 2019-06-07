<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    /**
     * Test if the login page shows correctly
     */
    public function testLoginPageOpen()
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * Test if this server returns the good login error when trying to connect with wrong login
     */
    public function testWrongPasswordLogin()
    {
        $client = static::createClient();


        $client->followRedirects();

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Valider')->form();

        $form['_username'] = 'test';
        $form['_password'] = 'mdp';

        $crawler = $client->submit($form);

       $this->assertSame(1, $crawler->filter('div.alert.alert-danger')->count());


    }
}