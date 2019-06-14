<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class IssueControllerTest extends WebTestCase
{


    /**
     * Test redirection to login page when going to member page with no authenticated user
     */
    public function testGoToMemberPageWithoutLogin()
    {
        $client = self::createClient();

        $client->request('GET', '/member');

        $this->assertTrue($client->getResponse()->isRedirect('http://localhost/login'));
    }

}