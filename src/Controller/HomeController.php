<?php

namespace App\Controller;

use App\Enum\UserRoleEnum;
use App\Repository\CvRepository;
use App\Repository\PositionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PositionRepository $position, CvRepository $cv, UserRepository $user): Response
    {
        return $this->render('home/index.html.twig', [
            'positionNumber' => $position->count([]),
            'cvNumber' => $cv->count([]),
            'candidateNumber' => $user->count(['role' => UserRoleEnum::Candidate]),
            'recruiterNumber' => $user->count(['role' => UserRoleEnum::Recruiter]),
            'positions' => $position->findBy([], ['updatedAt' => 'ASC'], 10),
            'popularPositions' => $position->createQueryBuilder('p')
                ->leftJoin('p.cvs', 'cv')
                ->addSelect('COUNT(cv.id) AS HIDDEN cvCount')
                ->groupBy('p.id')
                ->orderBy('cvCount', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult(),
        ]);
    }

    #[Route('/theme/toggle', name: 'app_theme_toggle')]
    public function toggleTheme(Request $request): Response
    {
        $currentTheme = $request->cookies->get('theme', 'light');

        $newTheme = $currentTheme === 'dark' ? 'light' : 'dark';

        $response = $this->redirectToRoute('app_home');

        $response->headers->setCookie(
            Cookie::create('theme')
                ->withValue($newTheme)
                ->withExpires(new \DateTimeImmutable('+1 year'))
                ->withPath('/')
        );

        return $response;
    }
}
