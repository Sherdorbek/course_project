<?php

namespace App\Controller;

use App\Entity\AttributeCv;
use App\Entity\User;
use App\Entity\UserAttribute;
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

final class ProfileController extends AbstractController
{

    #[Route('/profile', name: 'user_profile', methods: ["GET", "POST"])]
    public function index(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $em,
        TurboFrame $turboFrame, 
    ): Response {
        if (!$user->isProfileSetUp()) {
            return $this->redirectToRoute('user_profile_setup');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
        EntityManagerInterface $em,
        TurboFrame $turboFrame, 
    ): Response {
        if (!$user->isProfileSetUp()) {
            return $this->redirectToRoute('user_profile_setup');
        }

        

        // if ($turboFrame->isFrameRequest()) {
        //     return $this->render('profile/_form.html.twig', [
        //     ], new Response(null, 200));
        // }

        return $this->render('profile/attrs.html.twig', [
        ]);
    }


}
