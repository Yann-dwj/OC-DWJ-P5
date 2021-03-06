<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageLiaisonType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class LiaisonController extends AbstractController
{
    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var MessageRepository
     */
    private $messageRepository;

    public function __construct(UserRepository $userRepository, MessageRepository $messageRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/liaison", name="teacher.liaison.index")
     * @IsGranted("ROLE_TEACHER")
     * @return Response
     */
    public function index()
    {
        $teacher = $this->getUser();
        $classrooms = $teacher->getClassrooms();
        $students = [];

        foreach ($classrooms as $classroom)
        {
            $students = $classroom->getUsers();
        }

        $messagesCount= [];

        foreach ($students as $student)
        {
            $messagesCount[$student->getId()] = count($this->messageRepository->findByLiaison($teacher, $student));
        }
        
        return $this->render('backoffice/teacher/liaison/index.html.twig', [
            'teacher' => $teacher,
            'students' => $students,
            'messagesCount' => $messagesCount
        ]);
    }

    /**
     * @Route("/liaison/{id}", name="liaison.show")
     * @IsGranted("ROLE_USER")
     * @param User $user
     * @return Response
     */
    public function show(User $student, Request $request)
    {
        $user = $this->getUser();

        if ($user->hasRole('ROLE_TEACHER'))
        {
            $teacher = $user;

            $messages = $this->messageRepository->findByLiaison($teacher, $student);

            $classrooms = $teacher->getClassrooms();
            foreach ($classrooms as $classroom)
            {
                $classroom;
            }

            if ($classroom === $student->getClassroom())
            {
                $message = new Message();
                $form = $this->createForm(MessageLiaisonType::class, $message);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid())
                {
                    $message->setTransmitter($teacher);
                    $message->setOpenTransmitter(true);
                    $message->setRecipient($student);
                    $message->setLiaison(true);
                    $this->entityManager->persist($message);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'Le message a été envoyé avec succès');
                    return $this->redirectToRoute('liaison.show', array('id' => $student->getId()));
                }
    
                return $this->render('backoffice/teacher/liaison/show.html.twig', [
                    'student' => $student,
                    'messages' => $messages,
                    'form' => $form->createView()
                ]);
            }
            else
            {
                throw new AccessDeniedException();
            }
        }
        elseif ($user->hasRole('ROLE_USER'))
        {
            $student = $user;
            $classroom = $student->getClassroom();
            $teacher = $classroom->getTeacher();
        
            $messages = $this->messageRepository->findByLiaison($teacher, $student);

            $classrooms = $teacher->getClassrooms();
            foreach ($classrooms as $classroom)
            {
                $classroom;
            }
    
            if ($classroom === $student->getClassroom())
            {
                $message = new Message();
                $form = $this->createForm(MessageLiaisonType::class, $message);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid())
                {
                    $message->setTransmitter($student);
                    $message->setOpenTransmitter(true);
                    $message->setRecipient($teacher);
                    $message->setLiaison(true);
                    $this->entityManager->persist($message);
                    $this->entityManager->flush();
                    $this->addFlash('success', 'Le message a été envoyé avec succès');
                    return $this->redirectToRoute('liaison.show', array('id' => $student->getId()));
                }
    
                return $this->render('backoffice/family/show.html.twig', [
                    'student' => $student,
                    'messages' => $messages,
                    'form' => $form->createView()
                ]);
            }
            else
            {
                throw new AccessDeniedException();
            }
        }
    }
}