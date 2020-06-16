<?php

namespace App\Controller;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ClassroomController extends AbstractController
{
    /**
     * @var ClassroomRepository
     */
    private $repository;

    public function __construct(ClassroomRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin/classroom", name="admin.classroom.index")
     * @return Response
     */
    public function index()
    {
        $classrooms = $this->repository->findAll();

        return $this->render('backoffice/admin/classroom/index.html.twig', [
            'classrooms' => $classrooms
        ]);
    }

    /**
     * @Route("/admin/classroom/create", name="admin.classroom.new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request)
    {
        // $admin = $this->getUser();
        // $user = new User();
        $classroom = new Classroom();
        // $form = $this->createForm(ClassroomType::class, $classroom);
        $form = $this->createForm(ClassroomType::class, $classroom, ['user'=> $this->getUser()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->persist($classroom);
            $this->entityManager->flush();
            $this->addFlash('success', 'utilisateur ajouté avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('backoffice/admin/classroom/new.html.twig',[
            'classroom' => $classroom,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/classroom/{id}", name="admin.classroom.delete", methods="DELETE")
     * @return Response
     */
    public function delete(Classroom $classroom, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $classroom->getId(), $request->get('_token')))
        {
            $this->entityManager->remove($classroom);
            $this->entityManager->flush();
            $this->addFlash('success', 'La classe a bien été supprimé');
        }

        return $this->redirectToRoute('admin.classroom.index');
    }
}