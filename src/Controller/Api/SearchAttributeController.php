<?php

namespace App\Controller\Api;

use App\Entity\AttributeCv;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class SearchAttributeController extends AbstractController
{
    #[Route('/api/search/attribute', name: 'api_search_attribute',methods:["GET"])]
    public function index(EntityManagerInterface $entityManager,SerializerInterface $serializer): JsonResponse
    {   
        $attributes = $entityManager->getRepository(AttributeCv::class)->findAll();
        $json_content = $serializer->serialize($attributes,'json',[
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['description','oneOfManies','category']
        ]);
        
        return JsonResponse::fromJsonString($json_content);
    }
}
