<?php

namespace App\Controller;

use App\Entity\AttributeCategory;
use App\Entity\Cv;
use App\Entity\CvAttribute;
use App\Entity\Position;
use App\Entity\User;
use App\Entity\UserAttribute;
use App\Enum\AttributeTypeEnum;
use App\Form\CvType;
use App\Repository\AttributeCvRepository;
use App\Repository\CvRepository;
use App\Service\FilestackImageUploader;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('{position}/cv')]
final class CvController extends AbstractController
{
    #[Route(name: 'app_cv_index', methods: ['GET'])]
    public function index(CvRepository $cvRepository): Response
    {
        return $this->render('cv/index.html.twig', [
            'cvs' => $cvRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cv_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Position $position,
        Request $request,
        AttributeCvRepository $attrRepo,
        EntityManagerInterface $entityManager,
        FilestackImageUploader $imageUploader,
    ): Response {
        if ($entityManager->getRepository(Cv::class)->findOneBy(['user' => $user, 'position' => $position])) {
            return $this->redirectToRoute('app_position_show', ['id' => $position->getId()]);
        }

        $userValues = [];
        foreach ($user->getUserAttributes() as $ua) {
            $userValues[$ua->getAttribute()->getId()] = $ua;
        }

        if ($request->isMethod('POST')) {

            $requestAttr = $request->request->all('attribute');
            $requestFiles = $request->files->all('attribute');
            $attrIds = array_merge(
                array_keys($requestAttr),
                array_keys($requestFiles)
            );
            $attributes = $attrRepo->findBy([
                'id' => $attrIds
            ]);

            foreach ($attributes as $attribute) {

                if ($attribute->getType() === AttributeTypeEnum::ImageType) {
                    $image = $requestFiles[$attribute->getId()] ?? null;
                    $value = $imageUploader->upload($image);
                } else {
                    $value = $requestAttr[$attribute->getId()];
                }

                if ($value === "") continue;


                if (array_key_exists($attribute->getId(), $userValues)) {
                    $userValues[$attribute->getId()]->setValue($value, $attribute->getType());
                } else {
                    $newValue = new UserAttribute();
                    $newValue->setUser($user);
                    $user->addUserAttribute($newValue);
                    $newValue->setAttribute($attribute);
                    $newValue->setValue($value, $attribute->getType());
                }
            }

            $cv = new Cv();
            $cv->setPosition($position);
            $position->addCv($cv);
            $cv->setUser($user);
            $user->addCv($cv);
            $entityManager->persist($cv);

            $entityManager->flush();
            return $this->redirectToRoute('user_profile');
        }

        return $this->render('cv/new.html.twig', [
            'user' => $user,
            'userValues' => $userValues,
            'position' => $position,
        ]);
    }

    #[Route('/{id}', name: 'app_cv_show', methods: ['GET'])]
    public function show(Cv $cv): Response
    {
        return $this->render('cv/show.html.twig', [
            'cv' => $cv,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cv_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cv $cv, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CvType::class, $cv);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_cv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cv/edit.html.twig', [
            'cv' => $cv,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cv_delete', methods: ['POST'])]
    public function delete(Request $request, Cv $cv, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $cv->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($cv);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_cv_index', [], Response::HTTP_SEE_OTHER);
    }
}
