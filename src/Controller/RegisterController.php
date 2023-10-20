<?php

namespace App\Controller;



use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $passwordHash): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $passwordHash->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_login');
        }


        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'title' => 'Inscription'

        ]);
    }
}
