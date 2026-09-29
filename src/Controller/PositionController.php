<?php

namespace App\Controller;

use App\Entity\AttributeCv;
use App\Entity\Position;
use App\Entity\PositionAttr;
use App\Form\PositionType;
use App\Repository\AttributeCvRepository;
use App\Repository\PositionRepository;
use App\Service\PositionAttributeSynchronizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/position')]
final class PositionController extends AbstractController
{
    #[Route(name: 'app_position', methods: ['GET'])]
    public function index(PositionRepository $positionRepository): Response
    {
        return $this->render('position/index.html.twig', [
            'positions' => $positionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_position_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $position = new Position();
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $attributes = $entityManager->getRepository(AttributeCv::class)->findBy(['isRemovable' => false]);
            foreach ($attributes as $attribute) {
                $position->addAttribute($attribute);
            }
            $position->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tashkent')));
            $entityManager->persist($position);

            $entityManager->flush();

            return $this->redirectToRoute('app_position_show', ['id' => $position->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/new.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/delete', name: 'app_position_delete_all', methods: ['POST'])]
    public function deleteAll(Request $request, EntityManagerInterface $entityManager): Response
    {
        $positionIds = $request->request->all('selectedPositions');
        $positions = $entityManager->getRepository(Position::class)->findBy(['id' => $positionIds]);
        foreach ($positions as $position) {
            $entityManager->remove($position);
        }
        $entityManager->flush();
        return $this->redirectToRoute('app_position', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/{id}', name: 'app_position_show', methods: ['GET'])]
    public function show(Position $position): Response
    {
        return $this->render('position/show.html.twig', [
            'position' => $position,
        ]);
    }

    #[Route('/{id}/dublicate', name: 'app_position_dublicate', methods: ['POST'])]
    public function dublicate(Position $position, EntityManagerInterface $entityManager): Response
    {
        $newPosition = new Position();
        $newPosition->setTitle($position->getTitle() . ' copy');
        $newPosition->setDescription($position->getDescription());
        $newPosition->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tashkent')));
        foreach ($position->getAttributes() as $value) {
            $newPosition->addAttribute($value);
        }

        $entityManager->persist($newPosition);
        $entityManager->flush();
        return $this->redirectToRoute('app_position_show', ['id' => $newPosition->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_position_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Position $position, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tashkent')));


            $entityManager->flush();
            return $this->redirectToRoute('app_position_show', ['id' => $position->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/edit.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit/attributes', name: 'app_position_edit_attributes', methods: ['GET', 'POST'])]
    public function editAttributes(Request $request, Position $position, AttributeCvRepository $attributeRepo, EntityManagerInterface $entityManager): Response
    {

        if ($request->getMethod() === "POST") {
            $position->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tashkent')));
            $attributes = $request->request->all('positionAttributes');
            $position->getAttributes()->clear();
            foreach ($attributes as $value) {
                $position->addAttribute($attributeRepo->findOneBy(['id' => $value]));
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_position_show', ['id' => $position->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/attribute.html.twig', [
            'position' => $position,
        ]);
    }

    #[Route('/{id}', name: 'app_position_delete', methods: ['POST'])]
    public function delete(Request $request, Position $position, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $position->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($position);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_position', [], Response::HTTP_SEE_OTHER);
    }

}
