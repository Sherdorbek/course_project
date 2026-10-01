<?php

namespace App\Controller;

use App\Entity\AttributeCv;
use App\Form\AttributeType;
use App\Repository\AttributeCvRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_RECRUITER')]
final class AttributeController extends AbstractController
{
    #[Route('/attributes', name: 'app_attributes')]
    public function index(AttributeCvRepository $attrRepo): Response
    {
        $attrs = $attrRepo->findAll();

        return $this->render('attribute/index.html.twig', [
            'attrs' => $attrs,
        ]);
    }

    #[Route('/attribute/new', name: 'app_attribute_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $attr = new AttributeCv();
        $form = $this->createForm(AttributeType::class, $attr);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($attr->getOneOfManies() as $option) {
                $option->setAttribute($attr);
            }
            $manager->persist($attr);
            $manager->flush();

            return $this->redirectToRoute('app_attributes', [
                'id' => $attr->getId(),
            ]);
        }

        return $this->render('attribute/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/attribute/{id<\d+>}/edit', name: 'app_attribute_edit')]
    public function edit(AttributeCv $attr, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AttributeType::class, $attr);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$attr->isRemovable()){
                $this->addFlash('notice','You can not edit this attribute');
                return $this->redirectToRoute('app_attributes');
            }
            $manager->flush();

            return $this->redirectToRoute('app_attributes');
        }

        return $this->render('attribute/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/attribute/delete', name: 'app_attribute_delete_all')]
    public function deleteAll(Request $request, EntityManagerInterface $manager): Response
    {
        $ids = $request->request->all('selectedAttr');
        if (!empty($ids)) {
            $repository = $manager->getRepository(AttributeCv::class);

            $attributes = $repository->findBy(['id' => $ids]);
            $deleted = 0;

            foreach ($attributes as $attribute) {
                if ($attribute->isRemovable()) {
                    $manager->remove($attribute);
                    $deleted++;
                } else {
                    $this->addFlash('notice', $attribute->getName() . ' attribute cannot be deleted');
                }
            }

            $manager->flush();
            if ($deleted > 0) {
                $this->addFlash('success', $deleted . ' attribute(s) deleted successfully.');
            }
        }

        return $this->redirect('app_attributes');
    }

}
