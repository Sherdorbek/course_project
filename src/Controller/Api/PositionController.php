<?php

namespace App\Controller\Api;

use App\Enum\AttributeTypeEnum;
use App\Repository\AttributeCvRepository;
use App\Repository\PositionRepository;
use App\Repository\UserAttributeRepository;
use Normalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class PositionController extends AbstractController
{
    #[Route('/api/position', name: 'app_api_position')]
    public function index(
        PositionRepository $posRepo, 
        Request $request, 
        SerializerInterface $serializer, 
        NormalizerInterface $normalizer,
        UserAttributeRepository $uaRepo
        ): JsonResponse
    {
        $auth = $request->headers->get('authorization');
        $accessToken = null;
        if ($auth) {
            $accessToken = trim(substr($auth, 7));
        }
        if (!$accessToken) {
            return $this->json(['error' => 'Not authorized'], 401);
        }

        $position = $posRepo->findOneBy(['accessToken' => $accessToken]);

        if (!$position) {
            return $this->json(['error' => 'No position found'], 404);
        }

        $arrayData = $normalizer->normalize($position);
        foreach ($arrayData['attributes'] as &$attribute) {
            if ($attribute['type']==='numeric'){
                $attribute['aggregate'] = $uaRepo->numericAggregates($attribute['id'],$arrayData['users']);
            }elseif ($attribute['type']==='string'){
                $attribute['aggregate'] = $uaRepo->popularString($attribute['id'],$arrayData['users']);
            }elseif ($attribute['type']==='one_of_many'){
                $attribute['aggregate'] = $uaRepo->dropdownPopular($attribute['id'],$attribute['oneOfManies'],$arrayData['users']);
            }else{
                $attribute['aggregate'] = [];
            }
        }

        // dd($arrayData);

        return $this->json($arrayData);
    }

    #[Route('/api/query', name: 'app_api_position_q')]
    public function dd(UserAttributeRepository $uaRepo, AttributeCvRepository $attrRepo): JsonResponse
    {
        $oo = [['id' => 1, 'value' => 'C2'], ['id' => 2, 'value' => 'C1']];

        return $this->json($uaRepo->dropdownPopular(7, $oo));
    }
}
