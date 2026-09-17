<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\UserRoleEnum;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;

final class OAuthController extends AbstractController
{
    #[Route('/connect/google', name: 'connect_google_start')]
    public function connectGoogle(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('google')
            ->redirect();
    }
    #[Route('/connect/facebook', name: 'connect_facebook_start')]
    public function connectFacebook(ClientRegistry $clientRegistry): RedirectResponse
    {
        return $clientRegistry
            ->getClient('facebook')
            ->redirect();
    }

    #[Route('/connect/google/check', name: 'connect_google_check')]
    public function connectGoogleCheck(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        Security $security
    ) {

        $client = $clientRegistry->getClient('google');

        try {
            $gUser = $client->fetchUser();
        } catch (Exception $e) {
            $this->addFlash('error', 'Google authentication failed: ' . $e->getMessage());
            return $this->redirectToRoute('app_login');
        }

        $email = $gUser->getEmail();

        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles([UserRoleEnum::Candidate->value]);
            $user->setFirstName($gUser->getFirstName());
            $user->setSurname($gUser->getLastName());
            $user->setIsVerified(true);
            $user->setPassword(bin2hex(random_bytes(32)));
            $entityManager->persist($user);
            $entityManager->flush();
        }

        $security->login($user);

        return $this->redirectToRoute('app_home');
    }
    
    #[Route('/connect/facebook/check', name: 'connect_facebook_check')]
    public function connectFacebookCheck(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        Security $security
    ) {

        $client = $clientRegistry->getClient('facebook');

        try {
            $gUser = $client->fetchUser();
        } catch (Exception $e) {
            $this->addFlash('error', 'Facebook authentication failed: ' . $e->getMessage());
            return $this->redirectToRoute('app_login');
        }

        $email = $gUser->getEmail();

        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles(['ROLE_USER']);
            $user->setFirstName($gUser->getFirstName());
            $user->setSurname($gUser->getLastName());
            $user->setIsVerified(true);
            $user->setPassword(bin2hex(random_bytes(32)));
        }

        $entityManager->persist($user);
        $entityManager->flush();

        $security->login($user);

        return $this->redirectToRoute('app_home');
    }
}
