<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SalesForceType;
use App\Service\SalesForceAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SalesForceController extends AbstractController
{


    #[Route('{user}/sales/force', name: 'app_sales_force_new')]
    public function index(User $user, Request $request, EntityManagerInterface $entityManager, SalesForceAccount $saccount): Response
    {
        if ($user->getSalesForceId() !== null) {
            $this->addFlash('notice', 'Data already send to Salesforce');
            return $this->redirectToRoute('user_profile', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(SalesForceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $data['email'] = $user->getEmail();
            $data['firstname'] = $user->getUserAttribute(1);
            $data['lastname'] = $user->getUserAttribute(2);
            $data['location'] = $user->getUserAttribute(3);
            $data['phoneNumber'] = $user->getUserAttribute(5);


            $data = $saccount->add($data);

            if ($data['success']) {
                $user->setSalesForceId($data['id']);
                $entityManager->flush();
                $this->addFlash('success', 'Data send to Salesforce');
            }

            return $this->redirectToRoute('user_profile', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
        }
        return $this->render('sales_force/index.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }
}
