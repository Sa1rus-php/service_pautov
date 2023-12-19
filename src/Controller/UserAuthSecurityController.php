<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserAuthSecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('app_index');
         }

         $error = $authenticationUtils->getLastAuthenticationError();

         $lastUsername = $authenticationUtils->getLastUsername();

         return $this->render('security/user-login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/user/register', name: 'app_user_register')]
    public function register()
    {
        return $this->render('security/user-register.html.twig');
    }

    #[Route(path: '/user/register/post', name: 'app_user_register_post')]
    public function registerPost(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine)
    {
        $user = new User();
        $user->setEmail($request->get('email'));
        $user->setFio($request->get('fio'));
        $user->setPhoneNumber($request->get('phone'));
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $request->get('phone')
            )
        );
        $user->setRoles(["ROLE_USER"]);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }
}
