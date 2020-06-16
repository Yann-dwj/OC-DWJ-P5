<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $classroom = $user->getClassroom();
        $users = $this->userRepository->findByRole();
        $students = $this->userRepository->findByClassroom($user->getClassroom());
        $receivedMessages = $this->messageRepository->findBy(['recipient' => $user, 'trash_recipient' => false], ['id' => 'desc']);
        $notOpenMessages = $this->messageRepository->findBy(['recipient' =>$user, 'open_recipient' => false], ['id' => 'desc']);
        $sendedMessages = $this->messageRepository->findBy(['transmitter' => $user, 'trash_transmitter' => false], ['id' => 'desc']);

        if($user->hasRole('ROLE_ADMIN'))
        {
            $articles = $this->articleRepository->findBy(['classroom' => null]);

            return $this->render('backoffice/admin/index.html.twig', [
                "numberOfAdmins" => count($users['admins']),
                "numberOfTeachers" => count($users['teachers']),
                "numberOfStudents" => count($users['students']),
                "numberOfArticles" => count($articles),
                'receivedMessages' => count($receivedMessages),
                'notOpenMessages' => count($notOpenMessages),
                'sendedMessages' => count($sendedMessages),
            ]);
        }
        elseif($user->hasRole('ROLE_TEACHER'))
        {
            $articles = $this->articleRepository->findBy(['classroom' => $classroom]);

            return $this->render('backoffice/teacher/index.html.twig', [
                'user' => $user,
                'numberOfStudents' => count($students),
                "numberOfArticles" => count($articles),
                'receivedMessages' => count($receivedMessages),
                'notOpenMessages' => count($notOpenMessages),
                'sendedMessages' => count($sendedMessages),
            ]);
        }
        else
        {
            $latestArticle = $this->articleRepository->findOneBy(['classroom' => $classroom], ['id' => 'DESC']);
            $teacher = $classroom->getTeacher();

            return $this->render('backoffice/family/index.html.twig', [
                'user' => $user,
                'teacher' => $teacher,
                'latestArticle' => $latestArticle,
                'receivedMessages' => count($receivedMessages),
                'notOpenMessages' => count($notOpenMessages),
                'sendedMessages' => count($sendedMessages),
            ]);
        }
    }
}