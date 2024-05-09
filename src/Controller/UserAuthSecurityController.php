<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function registerPost(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $doctrine, ValidatorInterface $validator)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $passwordConfirm = $request->get('password_confirm');

        // Валидация email на уникальность
        $userRepository = $doctrine->getRepository(User::class);
        $existingUser = $userRepository->findOneBy(['email' => $email]);

        if ($existingUser) {
            return $this->render('security/user-register.html.twig', ['error' => 'Користувач із таким email уже існує.']);
        }

        // Валидация пароля на совпадение
        $constraint = new EqualTo([
            'value' => $password,
            'message' => 'Пароль не совпадает.',
        ]);

        $violations = $validator->validate($passwordConfirm, $constraint);

        if (count($violations) > 0) {
            return $this->render('security/user-register.html.twig', ['error' => $violations[0]->getMessage()]);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setFio($request->get('fio'));
        $user->setPhoneNumber($request->get('phone'));
        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );
        $user->setRoles(["ROLE_USER"]);

        $entityManager = $doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_index');
    }
}
