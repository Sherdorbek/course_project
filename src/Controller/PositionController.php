<?php

namespace App\Controller;

use App\Entity\Position;
use App\Entity\PositionAttr;
use App\Form\PositionType;
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
            $position->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tashkent')));

            $entityManager->flush();

            return $this->redirectToRoute('app_position', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/new.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_position_show', methods: ['GET'])]
    public function show(Position $position): Response
    {
        return $this->render('position/show.html.twig', [
            'position' => $position,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_position_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Position $position, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PositionType::class, $position);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $position->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Tashkent')));

            $entityManager->flush();

            return $this->redirectToRoute('app_position', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('position/edit.html.twig', [
            'position' => $position,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_position_delete', methods: ['POST'])]
    public function delete(Request $request, Position $position, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $position->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($position);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_position_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/delete', name: 'app_position_delete_all', methods: ['POST'])]
    public function deleteAll(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->redirectToRoute('app_position_index', [], Response::HTTP_SEE_OTHER);
    }

    //
    // ATTRIBUTE ROUTS
    //

    #[Route('/{id}/edit/attributes', name: 'app_position_attributes', methods: ['GET'])]
    public function editAttribute(Request $request, Position $position): Response
    {


        return $this->render('position/attribute/index.html.twig', [
            'position' => $position,
            'attributes' => $position->getPositionAttrs()
        ]);
    }
}
