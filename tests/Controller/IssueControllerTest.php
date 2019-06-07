<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class IssueControllerTest extends WebTestCase
{



    /**
     * Test if the main member page
     */
    public function testMemberPage()
    {
        $client = static::createClient();


        $crawler = $client->request('GET', '/member');

        $this->assertSame(302, $client->getResponse()->getStatusCode());
    }



}