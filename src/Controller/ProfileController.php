<?php

namespace App\Controller;

use App\Entity\AttributeCv;
use App\Entity\User;
use App\Entity\UserAttribute;
use App\Enum\AttributeTypeEnum;
use App\Form\SetupUserType;
use App\Form\UserProfileType;
use App\Repository\AttributeCvRepository;
use App\Repository\UserAttributeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\Turbo\TurboFrame;
use App\Service\FilestackImageUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'user_profile', methods: ["GET", "POST"])]
    public function index(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $em,
        TurboFrame $turboFrame,
        FilestackImageUploader $imageUploader,
    ): Response {
        if (!$user->isProfileSetUp()) {
            return $this->redirectToRoute('user_profile_setup');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($form->get('userAttributes') as $userAttributeForm) {
                if (!$userAttributeForm->has('imageFile')) {
                    continue;
                }

                $image = $userAttributeForm->get('imageFile')->getData();

                if ($image instanceof UploadedFile) {
                    $userAttributeForm
                        ->getData()
                        ->setValImage($imageUploader->upload($image));
                }
            }
            $em->flush();
        }

        if ($turboFrame->isFrameRequest()) {
            return $this->render('profile/_form.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ], new Response(null, $form->isSubmitted() && !$form->isValid() ? 422 : 200));
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }

    #[Route('/profile/setup', name: 'user_profile_setup')]
    public function setup(#[CurrentUser] User $user, Request $request, EntityManagerInterface $em): Response
    {
        if ($user->isProfileSetUp()) {
            return $this->redirectToRoute('user_profile');
        }

        $form = $this->createForm(SetupUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mandatoryAttrs = $em->getRepository(AttributeCv::class)->findBy(['isRemovable' => false], ['id' => 'ASC']);

            foreach ($mandatoryAttrs as $attribute) {

                $submittedValue = $form->get('attribute_' . $attribute->getId())->getData();

                $attributeValue = new UserAttribute();
                $attributeValue->setUser($user);
                $attributeValue->setAttribute($attribute);
                $attributeValue->setValue($submittedValue, $attribute->getType());
                $user->addUserAttribute($attributeValue);
            }
            $user->setProfileSetUp(true);
            $em->flush();


            return $this->redirectToRoute('app_home');
        }

        return $this->render('profile/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/profile/attribute', name: 'user_add_attribute', methods: ["GET", "POST"])]
    public function searchAttribute(
        #[CurrentUser] User $user,
        Request $request,
        AttributeCvRepository $attrManager,
        UserAttributeRepository $uaManager,
        EntityManagerInterface $em,
    ): Response {
        if (!$user->isProfileSetUp()) {
            return $this->redirectToRoute('user_profile_setup');
        }

        if ($request->getMethod() === "POST") {
            $attributeId = $request->request->get('selectedAttribute');
            $attribute = $attrManager->findOneBy(['id' => $attributeId]);
            if (is_null($uaManager->findOneBy(['user' => $user, 'attribute' => $attribute]))) {
                $newUa = new UserAttribute();
                $newUa->setAttribute($attribute);
                $newUa->setUser($user);
                if ($attribute->getType() === AttributeTypeEnum::BoolType)
                    $newUa->setValue(false, $attribute->getType());
                $user->addUserAttribute($newUa);
                $em->flush();
                $this->addFlash('success', 'New attribute added');
            } else {
                $this->addFlash('notice', 'User already has this attribute');
            }

            $em->flush();
            return $this->redirectToRoute('user_profile');
        }
        $q = $request->query->get('q') ?? 0;
        $attributes = $attrManager->searchByPrefix($q);

        return $this->render('profile/search.html.twig', [
            'searchAttributes' => $attributes,
        ]);
    }

     #[Route('/profile/attribute/remove', name: 'user_remove_attribute', methods: ["GET", "POST"])]
    public function deleteAttribute(
        #[CurrentUser] User $user,
        Request $request,
        AttributeCvRepository $attrManager,
        UserAttributeRepository $uaManager,
        EntityManagerInterface $em,
    ): Response {
        
        if ($request->getMethod() === "POST") {
            $uaIndexes = $request->request->all('selectedUserAttributes');
            $uas = $uaManager->findBy(['id'=>$uaIndexes]);
            foreach ($uas as $value) {
                $user->removeUserAttribute($value);
            }
            $em->flush();
            return $this->redirectToRoute('user_profile');
        }


        return $this->render('profile/remove.html.twig', [
            'user' => $user,
        ]);
    }
}
