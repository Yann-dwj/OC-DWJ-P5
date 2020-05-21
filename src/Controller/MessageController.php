<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Message;
use App\Form\MessageType;
use App\Form\MessageReplyType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MessageController extends AbstractController
{
    /**
     *
     * @var MessageRepository
     */
    private $repository;

    public function __construct(UserRepository $userRepository, MessageRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/message", name="admin.message.index")
     * @return Response
     */
    public function index()
    {
        $user = $this->getUser();
        $receivedMessages = $this->repository->findBy(['recipient' => $user, 'trash_recipient' => false], ['id' => 'desc']);
        $sendedMessages = $this->repository->findBy(['transmitter' => $user, 'trash_transmitter' => false], ['id' => 'desc']);

        return $this->render('backend/message/index.html.twig', [
            'receivedMessages' => $receivedMessages,
            'sendedMessages' => $sendedMessages,
        ]);
    }

    /**
     * @Route("/message/show/{id}", name="admin.message.show")
     * @param Message $message
     * @return Response
     */
    public function show(Message $message)
    {
        // TODO attention on ne doit pas pouvoir voir le message de quelqu'un d'autre
 
        if($message->isOwner($this->getUser()))
        {
            if($message->getRecipient() === $this->getUser() && $message->getOpenRecipient() === false)
            {
                $message->setOpenRecipient(true);
                $this->entityManager->flush();
            }

            if($message->getRecipient() === $this->getUser())
            {
                return $this->render('backend/message/received.html.twig', compact('message'));
            }
            else
            {
                return $this->render('backend/message/sended.html.twig', compact('message'));   
            }
        }
        else
        {
            throw new AccessDeniedException();
        }

    }

    /**
     * @Route("/message/create", name="admin.message.new")
     */
    public function new(Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message, ['user'=> $this->getUser()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setTransmitter($this->getUser());
            $message->setOpenTransmitter(true);
            $message->setLiaison(false);
            $this->entityManager->persist($message);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le message a été envoyé avec succès');
            return $this->redirectToRoute('admin.message.index');
        }

        return $this->render('backend/message/new.html.twig',[
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/message/{id}/reply", name="admin.message.reply", methods="GET|POST")
     * @param Message $message
     * @param Request $request
     * @return Response
     */
    public function reply(Message $message, Request $request)
    {
        $reply = new Message();
        $form = $this->createForm(MessageReplyType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $reply->setTransmitter($this->getUser());
            $reply->setRecipient($message->getTransmitter());
            $reply->setSubject('RE:' . $message->getSubject());
            $reply->setOpenTransmitter(true);
            $reply->setLiaison(false);
            $this->entityManager->persist($reply);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le message a été envoyé avec succès');
            return $this->redirectToRoute('admin.message.index');
        }

        return $this->render('backend/message/reply.html.twig',[
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/message/{id}", name="admin.message.delete", methods="DELETE")
     * @param Message $message
     * @return Response
     */
    public function delete(Message $message, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->get('_token')))
        {
            if($message->getTransmitter() === $this->getUser() && $message->getTrashTransmitter() === false)
            {
                $message->setTrashTransmitter(true);
                $this->entityManager->flush();
                $this->addFlash('success', 'Le message a été supprimé avec succès');
            }

            if($message->getRecipient() === $this->getUser() && $message->getTrashRecipient() === false)
            {
                $message->setTrashRecipient(true);
                $this->entityManager->flush();
                $this->addFlash('success', 'Le message a été supprimé avec succès');
            }

            if ($message->getTrashTransmitter() === true && $message->getTrashRecipient() === true && $message->getLiaison() === false)
            {
                $this->entityManager->remove($message);
                $this->entityManager->flush();
            }
        }

        return $this->redirectToRoute('admin.message.index');
    }
}