<?php

namespace App\Controller\Teacher;

use App\Entity\User;
use App\Form\UserType;
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

class TeacherLiaisonController extends AbstractController
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
     * @return Response
     */
    public function index()
    {
        $teacher = $this->getUser();
        $students = $this->userRepository->findByClassroom($teacher->getClassroom());

        foreach ($students as $student)
        {
            $messages = $this->messageRepository->findByLiaison($teacher, $student);
            $numberOfMessages = count($messages);
            dump($numberOfMessages);
        }
        
        return $this->render('backend/teacher/liaison/index.html.twig', [
            'students' => $students,
            'numberOfMessages' => $numberOfMessages
        ]);
    }

    /**
     * @Route("/liaison/{id}", name="teacher.liaison.show")
     * @param User $user
     * @return Response
     */
    public function show(User $student, Request $request)
    {
        $teacher = $this->getUser();
        $messages = $this->messageRepository->findByLiaison($teacher, $student);
        // $messages = $this->messageRepository->findBy(['transmitter' => $teacher, 'recipient' => $student], ['id' => 'desc']);
        // $messagesSended = $this->messageRepository->findBy(['transmitter' => $teacher, 'recipient' => $student], ['id' => 'desc']);
        // $messagesReceived = $this->messageRepository->findBy(['transmitter' => $student, 'recipient' => $teacher], ['id' => 'desc']);
        
        if ($teacher->getClassroom() === $student->getClassroom())
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
                return $this->redirectToRoute('teacher.liaison.show', array('id' => $student->getId()));
            }

            return $this->render('backend/teacher/liaison/show.html.twig', [
                'student' => $student,
                'messages' => $messages,
                'form' => $form->createView()
                // 'messagesSended' => $messagesSended,
                // 'messagesReceived' => $messagesReceived
            ]);
        }
        else
        {
            throw new AccessDeniedException();
        }
    }
}