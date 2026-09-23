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
            foreach ($attr->getOneOfManies() as $option){
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

            foreach ($attributes as $attribute) {
                $manager->remove($attribute);
            }

            $manager->flush();
            $this->addFlash('success', count($attributes).' attribute(s) deleted successfully.');
        }

        return $this->redirect('app_attributes');
    }

    #[Route('/attribute/{id<\d+>}/delete', name: 'app_attribute_delete')]
    public function delete(AttributeCv $attr, Request $request, EntityManagerInterface $manager): Response
    {
        if ($request->isMethod('POST')) {
            $manager->remove($attr);
            $manager->flush();

            $this->addFlash('notice', 'Product deleted successfully');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/delete.html.twig', [
            'id' => $attr->getId(),
        ]);
    }
}
