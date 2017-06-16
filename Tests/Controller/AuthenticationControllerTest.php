<?php

namespace triguk\AuthenticationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthenticationControllerControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertContains('name="_username"', $client->getResponse()->getContent());
    }
    
    public function testLogout()
    {
        $client = self::createClient();
        
        $client->request('GET', '/logout');

        $this->assertTrue($client->getResponse()->isSuccessful()||$client->getResponse()->isRedirect());
    }

    public function testRegister()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/register');

        $this->assertContains('Password_second', $client->getResponse()->getContent());
    }    
    
}
