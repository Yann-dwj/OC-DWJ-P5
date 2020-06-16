<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Message;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
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
    public function index(): Response
    {
        $users = $this->repository->findByRole();

        return $this->render('backoffice/admin/user/index.html.twig', [
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
                $message->setContent('Bonjour ' . $user->getFirstName() . ', toute l\'équipe de Mon École vous souhaite la bienvenue. Decouvrez dès à present les différentes fonctionnalités de votre espace personnel. Les carnets de liaisons des vos élèves, la gestion du blog de votre classe et votre messagerie. En cas de difficultés n\'hésitez pas à le faire savoir à l\'administrateur du site via la messagerie.');
            }
            elseif ($user->hasRole('ROLE_USER'))
            {
                $message->setContent('Bonjour ' . $user->getFirstName() . ', toute l\'équipe de Mon École vous souhaite la bienvenue. Vous pouvez désormais consulter le carnet de liaison numerique mis à votre dispoition pour correspondre avec l\'instituteur. Vous avez également accès au blog de la classe, n\'hesitez pas à vous connectez régulièrement pour le consulter. A bientot.');
            }
            else
            {
                $message->setContent('Bonjour ' . $user->getFirstName() . ', toute l\'équipe de Mon École vous souhaite la bienvenue.');
            }
            $this->entityManager->persist($message);

            $this->entityManager->flush();
            $this->addFlash('success', 'utilisateur ajouté avec succès');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('backoffice/admin/user/new.html.twig',[
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