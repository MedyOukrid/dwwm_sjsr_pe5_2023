<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository
    ){    }
    #[Route('/profil/{name}', name: 'app_user')]
    public function show(): Response
    {
        $user = $this->userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $user,
        ]);
    }
}
