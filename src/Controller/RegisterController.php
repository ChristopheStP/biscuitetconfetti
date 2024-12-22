<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                type:'success', 
                message:'Votre compte a bien été créé, veuillez vous connecter.'
            );

            //Envoi d'un mail de confirmation d'inscription
            $mail = new Mail();
            $vars = [
                'firstname' => $user->getFirstname(),
            ];
            $mail->send($user->getEmail(), $user->getFirstname() .' '.$user->getLastname(), 'Bienvenue sur Biscuit et confetti', 'welcome.html', $vars);

            return $this->redirectToRoute('app_login');
        }

       
        //si le formulaire est soumis alors :
            //Tu enregistres les données du formulaire en base de données
            //Tu envoies un message de confirmation du compte créé

        return $this->render('register/index.html.twig', [
            'registerForm' => $form->createView(),
        ]);
    }
}

/*NAMESPACE
Je définis un répertoire*/
/*USE
Je l'appelle*/