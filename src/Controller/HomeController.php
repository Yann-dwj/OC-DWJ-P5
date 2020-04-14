<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     *
     * @var ArticleRepository
     */
    private $repository;
    
    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/", name="home")
     * @param ArticleRepository $repository
     * @return Response
     */
    public function index(): Response
    {
        $articles = $this->repository->findAll();
        dump($articles);

        return $this->render('frontend/home.html.twig', [
            'articles' => $articles
        ]);
    }
}