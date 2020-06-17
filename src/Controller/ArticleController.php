<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\ArticleImageType;
use App\Repository\ArticleRepository;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ArticleController extends AbstractController
{
    /**
     *
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(ArticleRepository $repository, ClassroomRepository $classroomRepository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->classroomRepository = $classroomRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param Article $classroom
     * @Route("/blog/{id}", name="blog.index")
     */
    public function showBlog(Article $classroom)
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_TEACHER') || $user->getClassroom()->getId() === $classroom->getId())
        {
            $articles = $this->repository->findBy(['classroom' => $classroom], ['id' => 'desc']);
            $classroom = $this->classroomRepository->findOneBy(['id' => $classroom]);
    
            return $this->render('backoffice/blog/index.html.twig', [
                'articles' => $articles,
                'classroom' => $classroom
            ]);
        }
        else
        {
            throw new AccessDeniedException();
        }

    }

    /**
     * @param Article $article
     * @Route("/blog/article/{id}", name="blog.article")
     */
    public function showArticle(Article $article)
    {
        $article = $this->repository->findOneBy(['id' => $article]);

        return $this->render('backoffice/blog/article.html.twig', [
            'article' => $article,
        ]);
    }


    /**
     * @Route("/blogs", name="blogs.index")
     */
    public function showAllBlog()
    {
        $classrooms = $this->classroomRepository->findAll();

        return $this->render('backoffice/article/blogs.html.twig', [
            'classrooms' => $classrooms
        ]);
    }

    /**
     * @Route("/article", name="article.index")
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $classroom = $user->getClassroom();
        dump($classroom);

        if($user->hasRole('ROLE_TEACHER'))
        {
            $articles = $this->repository->findBy(['classroom' => $classroom], ['id' => 'desc']);
        }
        else
        {
            $articles = $this->repository->findBy(['classroom' => null]);
        }

        return $this->render('backoffice/article/index.html.twig', compact('articles'));
    }

    /**
     * @Route("/article/create", name="article.new")
     */
    public function new(Request $request)
    {
        $user = $this->getUser();
        $classroom = $this->getUser()->getclassroom();
        $article = new Article();
        
        if($user->hasRole('ROLE_ADMIN'))
        {
            $form = $this->createForm(ArticleType::class, $article);
        }
        else
        {
            $form = $this->createForm(ArticleImageType::class, $article);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // $article->setClassroom($classroom);
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'article ajouté avec succès');
            return $this->redirectToRoute('article.index');
        }

        if($user->hasRole('ROLE_ADMIN'))
        {
            return $this->render('backoffice/article/new.html.twig',[
                'article' => $article,
                'form' => $form->createView()
            ]);
        }
        else
        {
            return $this->render('backoffice/article/newImage.html.twig',[
                'article' => $article,
                'form' => $form->createView()
            ]);        
        }
    }

    /**
     * @Route("/article/{id}", name="article.edit", methods="GET|POST")
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function edit(Article $article, Request $request)
    {
        $user = $this->getUser();

        if($user->hasRole('ROLE_ADMIN'))
        {
            $form = $this->createForm(ArticleType::class, $article);
        }
        else
        {
            $form = $this->createForm(ArticleImageType::class, $article);
        }   
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
            $this->addFlash('success', 'article modifié avec succès');
            return $this->redirectToRoute('article.index');
        }

        if($user->hasRole('ROLE_ADMIN'))
        {
            return $this->render('backoffice/article/edit.html.twig',[
                'article' => $article,
                'form' => $form->createView()
            ]);
        }
        else
        {
            return $this->render('backoffice/article/editImage.html.twig',[
                'article' => $article,
                'form' => $form->createView()
            ]);        
        }
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