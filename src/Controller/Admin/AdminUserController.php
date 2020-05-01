<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminUserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/admin/user", name="admin.user.index")
     * @return Response
     */
    public function index()
    {
        $users = $this->repository->findAll();
      
        $admins = [];
        $teachers = [];
        $families = [];

        foreach($users as $user)
        {
            if($user->hasRole('ROLE_ADMIN'))
            {
                $admins[] = $user;
            }
            if($user->hasRole('ROLE_USER'))
            {
                $families[] = $user;
            }
            if($user->hasRole('ROLE_TEACHER'))
            {
                $teachers[] = $user;
            }
        }

        return $this->render('backend/admin/user/index.html.twig', [
            'admins' => $admins,
            'teachers' => $teachers,
            'families' => $families,
            // 'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/create", name="admin.user.new")
     */
    public function new(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($this->encoder->encodePassword($user, $form->get('password')->getData()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'utilisateur ajouté avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('backend/admin/user/new.html.twig',[
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="admin.user.delete", methods="DELETE")
     * @param User $user
     * @return Response
     */
    public function delete(User $user, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token')))
        {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'utilisateur supprimé avec succès');
        }

        return $this->redirectToRoute('admin.user.index');
    }
}