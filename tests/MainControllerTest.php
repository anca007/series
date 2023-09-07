<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testHomePageIsWorking(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome in Series');
    }

    public function testCreateSerieIsWorkingIfUserNotLogged(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/serie/new');

        $this->assertResponseRedirects("/login", 302);
    }

    public function testCreateSerieIsWorkingIfUserLogged()
    {

        $client = static::createClient();

        //accès au méga container des services Symfony
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["email" => "sly@mail.com"]);
        //simule une connexion
        $client->loginUser($user);

        $crawler = $client->request('GET', '/serie/new');

        $this->assertResponseIsSuccessful();
    }

    public function testAccountCreation(){

        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $client->submitForm("Register", [
            "registration_form[email]" => "toto@mail.com",
            "registration_form[firstname]" => "toto",
            "registration_form[lastname]" => "machin",
            "registration_form[plainPassword]" => "123456"
        ]);

        $this->assertResponseRedirects("/serie/list", 302);

    }
}
