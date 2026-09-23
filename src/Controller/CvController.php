<?php

namespace App\Controller;

use App\Entity\AttributeCategory;
use App\Entity\Cv;
use App\Entity\CvAttribute;
use App\Entity\Position;
use App\Entity\User;
use App\Enum\AttributeTypeEnum;
use App\Form\CvType;
use App\Repository\CvRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cv')]
final class CvController extends AbstractController
{
    #[Route(name: 'app_cv_index', methods: ['GET'])]
    public function index(CvRepository $cvRepository): Response
    {
        return $this->render('cv/index.html.twig', [
            'cvs' => $cvRepository->findAll(),
        ]);
    }

    #[Route('/{id<\d+>}/new', name: 'app_cv_new', methods: ['GET', 'POST'])]
    public function new(Position $position, Request $request, EntityManagerInterface $entityManager): Response
    {
        $cv = new Cv();
        $cv->setPosition($position);
        if ($request->getMethod() === "POST") {

            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'a@a.com']);
            $cvValues = $request->request->all('attribute');

            foreach ($position->getAttributes() as $attribute) {

                $type = $attribute->getType();
                $value = $cvValues[$type->value][$attribute->getId()];
                $newCvAttribute = new CvAttribute();
                $newCvAttribute->setAttribute($attribute);

                if ($type === AttributeTypeEnum::StringType) {
                    $newCvAttribute->setValString($value);
                } elseif ($type === AttributeTypeEnum::TextType) {
                    $newCvAttribute->setValText($value);
                } elseif ($type === AttributeTypeEnum::ImageType) {
                    $newCvAttribute->setValImage($value);
                } elseif ($type === AttributeTypeEnum::NumericType) {
                    $newCvAttribute->setValNumber($value);
                } elseif ($type === AttributeTypeEnum::DateType) {
                    $newCvAttribute->setValDate(new DateTimeImmutable($value));
                } elseif ($type === AttributeTypeEnum::PeriodType) {
                    $newCvAttribute->setValDate(new DateTimeImmutable($value[0]));
                    $newCvAttribute->setValDatePeriod(new DateTimeImmutable($value[1]));
                } elseif ($type === AttributeTypeEnum::BoolType) {
                    $newCvAttribute->setValBool($value);
                } elseif ($type === AttributeTypeEnum::OneOfMany) {
                    $newCvAttribute->setValDropdown($value);
                }
                $cv->addAttribute($newCvAttribute);
            }
            $cv->setLikes(0);
            $cv->setUser($user);
            $cv->setPosition($position);
            $cv->setFirstName('John');
            $cv->setSecondName('Doe');
            $cv->setEmail($user->getEmail());

            $entityManager->persist($cv);
            $entityManager->flush();

            return $this->redirectToRoute('app_cv_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cv/new.html.twig', [
            'cv' => $cv,
            'categories' => $entityManager->getRepository(AttributeCategory::class)->findAll(),
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
