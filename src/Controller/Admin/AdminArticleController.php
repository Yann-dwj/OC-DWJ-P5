<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
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
     * @Route("/admin/article", name="admin.article.index")
     * @return Response
     */
    public function index()
    {
        $articles = $this->repository->findAll();
        return $this->render('backend/admin/article/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/admin/article/create", name="admin.article.new")
     */
    public function new(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'article ajouté avec succès');
            return $this->redirectToRoute('admin.article.index');
        }

        return $this->render('backend/admin/article/new.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="admin.article.edit", methods="GET|POST")
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
            return $this->redirectToRoute('admin.article.index');
        }

        return $this->render('backend/admin/article/edit.html.twig',[
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article/{id}", name="admin.article.delete", methods="DELETE")
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

        return $this->redirectToRoute('admin.article.index');
    }
}