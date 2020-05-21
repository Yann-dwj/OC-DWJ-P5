<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     *
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(ArticleRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/article", name="article.index")
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $classroom = $user->getClassroom();

        if($user->hasRole('ROLE_TEACHER'))
        {
            $articles = $this->repository->findBy(['classroom' => $classroom], ['id' => 'desc']);
        }
        else
        {
            $articles = $this->repository->findBy(['classroom' => null]);
        }

        return $this->render('backend/article/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/article/create", name="article.new")
     */
    public function new(Request $request)
    {
        $classroom = $this->getUser()->getclassroom();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        dump($classroom);

        if ($form->isSubmitted() && $form->isValid())
        {
            $article->setClassroom($classroom);
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'article ajouté avec succès');
            return $this->redirectToRoute('article.index');
        }

        return $this->render('backend/article/new.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{id}", name="article.edit", methods="GET|POST")
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function edit(Article $article, Request $request)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            $this->addFlash('success', 'article modifié avec succès');
            return $this->redirectToRoute('article.index');
        }

        return $this->render('backend/article/edit.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/article/{id}", name="article.delete", methods="DELETE")
     * @param Article $article
     * @return Response
     */
    public function delete(Article $article, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token')))
        {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'article supprimé avec succès');
        }

        return $this->redirectToRoute('article.index');
    }
}