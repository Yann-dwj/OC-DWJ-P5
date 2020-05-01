<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminMessageController extends AbstractController
{
    /**
     *
     * @var MessageRepository
     */
    private $repository;

    public function __construct(MessageRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/message", name="admin.message.index")
     * @return Response
     */
    public function index()
    {
        $messages = $this->repository->findAll();
        return $this->render('backend/admin/message/index.html.twig', compact('messages'));
    }

    /**
     * @Route("/admin/message/create", name="admin.message.new")
     */
    public function new(Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setTransmitter($this->getUser());
            $this->entityManager->persist($message);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le message a été envoyé avec succès');
            return $this->redirectToRoute('admin.message.index');
        }

        return $this->render('backend/admin/message/new.html.twig',[
            'message' => $message,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/message/{id}", name="admin.message.delete", methods="DELETE")
     * @param Message $message
     * @return Response
     */
    public function delete(Message $message, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->get('_token')))
        {
            $this->entityManager->remove($message);
            $this->entityManager->flush();
            $this->addFlash('success', 'Le message a été supprimé avec succès');
        }

        return $this->redirectToRoute('admin.message.index');
    }
}