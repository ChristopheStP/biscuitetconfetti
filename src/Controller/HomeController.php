<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Repository\BodyRepository;
use App\Repository\CategoryRepository;
use App\Repository\HeaderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HeaderRepository $headerRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository, BodyRepository $bodyRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'headers' => $headerRepository->findAll(),
            'productsInHomepage' => $productRepository->findByIsHomepage(true),
            'categoriesInHomepage' => $categoryRepository->findByIsHomepage(true),
            'bodies' => $bodyRepository->findAll(),
        ]);
    }
}

