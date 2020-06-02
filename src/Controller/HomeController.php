<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ArticleRepository;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function index(Request $request, ContactNotification $notification): Response
    {
        $articles = $this->repository->findBy(['classroom' => null]);

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('home');
        }

        return $this->render('frontend/home.html.twig', [
            'articles' => $articles,
            'form' => $form->createView()
        ]);
    }
}