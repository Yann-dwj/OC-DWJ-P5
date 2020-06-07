<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Message;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $users = $this->repository->findByRole();

        return $this->render('backend/admin/user/index.html.twig', [
            'admins' => $users['admins'],
            'teachers' => $users['teachers'],
            'students' => $users['students']
        ]);
    }

    /**
     * @Route("/admin/user/create", name="admin.user.new")
     */
    public function new(Request $request)
    {
        $admin = $this->getUser();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setPassword($this->encoder->encodePassword($user, $form->get('password')->getData()));
            $this->entityManager->persist($user);

            $message = new Message();
            $message->setTransmitter($admin);
            $message->setTrashTransmitter(true);
            $message->setRecipient($user);
            $message->setSubject('Bienvenue');

            if ($user->hasRole('ROLE_TEACHER'))
            {
                $message->setContent('Bonjour ' . $user->getFirstName() . ', toute l\'équipe de Mon École vous souhaite la bienvenue parmis nous. Découvrez dès à présent toutes les fonctionnalités de votre espace personnel.\n\n l\'outil "Carnet de Liaison" vous permet de communiquer avec l\'ensemble des élèves de votre classe et facilitera ainsi les échanges Parents/Instituteurs.\n\n l\'outil Blog vous permet de tenir à jour des articles d\'actualités de votre classe, il sera visible par l\'ensemble des parents et élèves de votre classe. toute l\équipe enseignant peut également le consulter et vous pouvez également suivre les blog des autres classe.\r\r L\'outil messagerie vous permet de communiquer avec les différents enseignants et vos parents d\'élèves. Pour ces derniers les messages envoyés sont automatiquement ajouté au carnet de liaison.\r\r Vous pouvez joindre également l\'adminitsrateur du site pour toutes vos questions techniques depuis la messagerie.');
            }
            elseif ($user->hasRole('ROLE_USER'))
            {
                $message->setContent('Bonjour ' . $user->getFirstName() . ', toute l\'équipe de Mon École vous souhaite la bienvenue');
            }
            else
            {
                $message->setContent('Bonjour ' . $user->getFirstName() . ', toute l\'équipe de Mon École vous souhaite la bienvenue');
            }
            $this->entityManager->persist($message);

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