<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserTest extends WebTestCase
{
    public function testSomething(): void
    {
        /*
         * 1. Créer un faux client (navigateur) de pointer vers une URL
         * 2. Remplir les champs du formulaire d'inscription
         * 3. Est-ce que tu peux regarder si dans ma page j'ai le message (alerte) suivante : 'Votre compte a bien été créé, veuillez vous connecter.'
         */

        // 1
        $client = static::createClient();
        $client->request('GET', '/inscription');

        // 2 (firstname, lastname, email, password, confirmation du password)
        $client->submitForm('Valider', [
            'register_user[email]' => 'julie@example.fr',
            'register_user[plainPassword][first]' => '123456',
            'register_user[plainPassword][second]' => '123456',
            'register_user[firstname]' => 'Julie',
            'register_user[lastname]' => 'Doe',
        ]);

        // FOLLOW
        $this->assertResponseRedirects('/connexion');
        $client->followRedirect();

        // 3
        $this->assertSelectorExists('div:contains("Votre compte a bien été créé, veuillez vous connecter.")');
    }
}
