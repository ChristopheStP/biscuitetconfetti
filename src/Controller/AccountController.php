<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        // Vérifier si l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Récupérer l'utilisateur connecté et ses commandes
        $user = $this->getUser();
        $orders = $this->orderRepository->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render('account/index.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/compte/modifier-mot-de-passe', name: 'app_account_modify_pwd')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash(
                type:'success', 
                message:'Votre mot de passe a bien été modifié.'
            );
        }

        return $this->render('account/password.html.twig', [
            'modifyPwd' => $form->createView(),
        ]);
    }
}
