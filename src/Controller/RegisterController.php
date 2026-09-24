<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/registration', name: 'app_registration')]
    public function index(): Response
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }
    #[Route('/register',name:'app_register',methods:['POST'])]
    public function register(Request $request,UserPasswordHasherInterface $passwordHasher):Response
    {
        $mail = $request->request->getString('mail');
        $role = $request->request->get('role');
        $name = $request->request->get('username');
        if ($role) {
            $role = ['ROLE_ADMIN'];
        }else{
            $role = ['ROLE_USER'];
        }
        $user = new User();
        $user->setRoles($role);
        $user->setPassword($passwordHasher->hashPassword($user,$request->request->get('pass')));
        $user->setUsername($name);
        $user->setMail($mail);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_accueil');
    }
}
