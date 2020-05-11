<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * @var MessageRepository
     */
    private $messageRepository;

    public function __construct(UserRepository $userRepository, ArticleRepository $articleRepository, MessageRepository $messageRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/dashboard", name="dashboard.index")
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $users = $this->userRepository->findByRole();
        $articles = $this->articleRepository->findAll();
        $receivedMessages = $this->messageRepository->findBy(['recipient' => $user, 'trash_recipient' => false], ['id' => 'desc']);
        $notOpenMessages = $this->messageRepository->findBy(['recipient' =>$user, 'open_recipient' => false], ['id' => 'desc']);
        $sendedMessages = $this->messageRepository->findBy(['transmitter' => $user, 'trash_transmitter' => false], ['id' => 'desc']);

        if($user->hasRole('ROLE_ADMIN'))
        {
            return $this->render('backend/admin/index.html.twig', [
                "numberOfAdmins" => count($users['admins']),
                "numberOfTeachers" => count($users['teachers']),
                "numberOfFamilies" => count($users['families']),
                "numberOfArticles" => count($articles),
                'receivedMessages' => count($receivedMessages),
                'notOpenMessages' => count($notOpenMessages),
                'sendedMessages' => count($sendedMessages),
            ]);
        }
        elseif($user->hasRole('ROLE_TEACHER'))
        {
            return $this->render('backend/teacher/index.html.twig', [
                'receivedMessages' => count($receivedMessages),
                'notOpenMessages' => count($notOpenMessages),
                'sendedMessages' => count($sendedMessages),
            ]);
        }
        else
        {
            return $this->render('backend/family/index.html.twig', [
                'receivedMessages' => count($receivedMessages),
                'notOpenMessages' => count($notOpenMessages),
                'sendedMessages' => count($sendedMessages),
            ]);
        }

    }
}