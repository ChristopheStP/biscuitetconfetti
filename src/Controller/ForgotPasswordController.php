<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ForgotPasswordFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ForgotPasswordController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_password')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        // 1. Formulaire
        $form = $this->createForm( ForgotPasswordFormType::class);

        $form->handleRequest($request);

        // 2. Traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
        // 3. Si l'email renseigné par l'utilisateur est bien en base de données
        $email = $form->get('email')->getData();
        $user = $userRepository->findOneByEmail($email);

        // 4. Envoyer une notification à l'utilisateur
            $this->addFlash('success', 'Si votre adresse email existe, vous recevrez un mail pour réinitialiser votre mot de passe.');

        // 5. Si user existe, on reset le mot de passe et on envoie par email le nouveau mot de passe
        if ($user) {
            // 5.a Créer un token qu'on va stocker en BDD
            $token = bin2hex(random_bytes(15));
            $user->setToken($token);

            $date = new DateTime();

            $date->modify('+10 minutes');

            $user->setTokenExpireAt($date);

            $this->em->flush();

            $mail = new Mail();
            $vars = [
                'link' => $this->generateUrl('app_password_update', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
            $mail->send($user->getEmail(), $user->getFirstname() .' '.$user->getLastname(), 'Modification de votre mot de passe', 'forgotpassword.html', $vars);
        }



        // 5. Si aucun email trouvé, on push une notification : Email introuvable (message plus vague par sécurité).
        }

        return $this->render('password/index.html.twig', [
            'forgotPasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/mot-de-passe/reset/{token}', name: 'app_password_update')]
    public function update(Request $request,UserRepository $userRepository, $token): Response
    {
        if (!$token) {
            return $this->redirectToRoute('app_password');
        }

        $user = $userRepository->findOneByToken($token);

        $now = new DateTime();
        if (!$user || $now > $user->getTokenExpireAt()) {
            return $this->redirectToRoute('app_password');
        }

        $form = $this->createForm( ResetPasswordFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setToken(null);
            $user->setTokenExpireAt(null);
            $this->em->flush();
            $this->addFlash(
                type:'success',
                message:'Votre mot de passe a bien été modifié.'
            );
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
