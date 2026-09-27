<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function profile(#[CurrentUser()] ?User $user): Response
    {
        if (null === $user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('profile/index.html.twig', []);
    }

    #[Route('/profile', name: 'user_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
