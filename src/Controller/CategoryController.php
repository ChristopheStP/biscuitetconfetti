<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/categorie/{slug}', name: 'app_category')]
    public function index($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBySlug($slug);
        // Repository : permet de faire des requêtes en base de données
        // 1 j'ouvre une connexion avec ma BDD
        // 2 connecte toi à la table category
        // 3 fais une action en base de données

        if (!$category) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    }
}
