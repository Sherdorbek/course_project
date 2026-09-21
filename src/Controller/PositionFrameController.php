<?php

namespace App\Controller;

use App\Repository\AttributeCvRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PositionFrameController extends AbstractController
{
    #[Route('/search/attribute', name: 'app_position_search_attribute')]
    public function index(Request $request, AttributeCvRepository $attributeCvRepository): Response
    {
        $prefix = $request->query->get('q') ?? '';
        // dd( $attributeCvRepository->searchByPrefix($prefix));
        return $this->render('position/_search-result.html.twig', [
            'searchAttributes' => $attributeCvRepository->searchByPrefix($prefix),
        ]);
    }
}
