<?php

namespace App\Controller;

use App\Entity\AttributeCv;
use App\Entity\AttributeValue;
use App\Enum\AttributeTypeEnum;
use App\Repository\AttributeCvRepository;
use App\Form\AttributeType;
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
        $form = $this->createForm(AttributeType::class,$attr);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $this->addFlash('notice', 'New attribute has been added');
            $manager->persist($attr);
            $manager->flush();

            return $this->redirectToRoute('app_attributes', [
                'id' => $attr->getId()
            ]);
        }

        return $this->render('attribute/new.html.twig', [
            'form' => $form
        ]);
    }
    
    #[Route('/attribute/{id<\d+>}/edit', name: 'app_attribute_edit')]
    public function edit(AttributeCv $attr, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AttributeType::class,$attr);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager->flush();

            $this->addFlash('notice','The attribute has been edited');

            return $this->redirectToRoute('app_attributes');
        }

        return $this->render('attribute/edit.html.twig',[
            'form'=>$form
        ]);

    }
}
