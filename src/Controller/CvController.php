<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Entity\Likes;
use App\Entity\Position;
use App\Entity\User;
use App\Entity\UserAttribute;
use App\Enum\AttributeTypeEnum;
use App\Form\CvType;
use App\Repository\AttributeCategoryRepository;
use App\Repository\AttributeCvRepository;
use App\Repository\CvRepository;
use App\Repository\LikesRepository;
use App\Service\FilestackImageUploader;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function PHPSTORM_META\map;

#[Route('{position}/cv')]
final class CvController extends AbstractController
{
    #[IsGranted('ROLE_RECRUITER')]
    #[Route('/', name: 'app_cv_index', methods: ['GET'])]
    public function index(Position $position): Response
    {
        return $this->render('cv/index.html.twig', [
            'position' => $position
        ]);
    }

    #[IsGranted('ROLE_CANDIDATE')]
    #[Route('/new', name: 'app_cv_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Position $position,
        Request $request,
        AttributeCvRepository $attrRepo,
        EntityManagerInterface $entityManager,
        FilestackImageUploader $imageUploader,
    ): Response {
        if ($tcv = $entityManager->getRepository(Cv::class)->findOneBy(['user' => $user, 'position' => $position])) {
            return $this->redirectToRoute('app_cv_edit', ['position' => $position->getId(), 'id' => $tcv->getId()]);
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

            $mandatoryAttrs = array_filter($attributes, fn($e) => !$e->isRemovable());
            foreach ($mandatoryAttrs as $attribute) {
                if (!array_key_exists($attribute->getId(), $requestAttr)) {
                    continue;
                }

                if ($requestAttr[$attribute->getId()] === '') {
                    $this->addFlash('notice', 'Fill manadatory fields');
                    return $this->redirectToRoute('app_cv_new', ['position' => $position->getId()]);
                }
            }

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
            $cv->setUpdatedAt(new DateTime());
            $position->addCv($cv);
            $cv->setUser($user);
            $user->addCv($cv);
            $user->setProfileSetUp(true);
            $entityManager->persist($cv);

            $entityManager->flush();
            return $this->redirectToRoute('app_cv_show', ['position' => $position->getId(), 'id' => $cv->getId()]);
        }

        return $this->render('cv/new.html.twig', [
            'user' => $user,
            'userValues' => $userValues,
            'position' => $position,
        ]);
    }

    #[IsGranted('ROLE_CANDIDATE')]
    #[Route('/{id}/edit', name: 'app_cv_edit', methods: ['GET', 'POST'])]
    public function edit(
        Cv $cv,
        Position $position,
        Request $request,
        AttributeCvRepository $attrRepo,
        EntityManagerInterface $entityManager,
        FilestackImageUploader $imageUploader,
    ): Response {

        $userValues = [];
        $user = $cv->getUser();
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
            $mandatoryAttrs = array_filter($attributes, fn($e) => !$e->isRemovable());
            foreach ($mandatoryAttrs as $attribute) {
                if (!array_key_exists($attribute->getId(), $requestAttr)) {
                    continue;
                }

                if ($requestAttr[$attribute->getId()] === '') {
                    $this->addFlash('notice', 'Fill manadatory fields');
                    return $this->redirectToRoute('app_cv_edit', ['position' => $position->getId(), 'id' => $cv->getId()]);
                }
            }

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

            $cv->setUpdatedAt(new DateTime());

            $entityManager->flush();
            return $this->redirectToRoute('app_cv_show', ['position' => $position->getId(), 'id' => $cv->getId()]);
        }

        return $this->render('cv/edit.html.twig', [
            'cv' => $cv,
            'user' => $user,
            'userValues' => $userValues,
            'position' => $position,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/{id}', name: 'app_cv_show', methods: ['GET'])]
    public function show(Position $position, Cv $cv, AttributeCategoryRepository $categoryManager): Response
    {
        $userValues = [];
        $firstName = '';
        $secondName = '';
        $location = '';
        $imagePath = '';
        $phoneNumber = '';
        $email = $cv->getUser()->getEmail();
        foreach ($cv->getUser()->getUserAttributes() as $ua) {
            switch ($ua->getAttribute()->getId()) {
                case 1:
                    $firstName = $ua;
                    break;
                case 2:
                    $secondName = $ua;
                    break;
                case 3:
                    $location = $ua;
                    break;
                case 4:
                    $imagePath = $ua;
                    break;
                case 5:
                    $phoneNumber = $ua;
                    break;
                default:
                    $userValues[$ua->getAttribute()->getId()] = $ua;
                    break;
            }
        }
        return $this->render('cv/show.html.twig', [
            'cv' => $cv,
            'position' => $position,
            'userValues' => $userValues,
            'firstname' => $firstName,
            'secondname' => $secondName,
            'location' => $location,
            'imagepath' => $imagePath,
            'phonenumber' => $phoneNumber,
            'email' => $email,
            'categories' => $categoryManager->findAll(),
        ]);
    }


    #[IsGranted('ROLE_RECRUITER')]
    #[Route('/', name: 'app_cv_delete', methods: ['POST'])]
    public function deleteAll(Position $position, Request $request, CvRepository $cvManager, EntityManagerInterface $entityManager): Response
    {
        $ids = $request->request->all('selectedCVs');
        $cvs = $cvManager->findBy(['id' => $ids]);

        foreach ($cvs as $cv) {
            $user = $cv->getUser();
            if ($user) {
                $user->removeCv($cv);
            }
            $entityManager->remove($cv);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_cv_index', ['position' => $position->getId()], Response::HTTP_SEE_OTHER);
    }

    #[IsGranted('ROLE_RECRUITER')]
    #[Route('/{id}/likes', name: 'app_cv_likes', methods: ['POST'])]
    public function likes(Position $position, Cv $cv, #[CurrentUser] User $user, LikesRepository $likeManager, EntityManagerInterface $em): Response
    {
        $like = $likeManager->findOneBy(['cv' => $cv, 'user' => $user]);
        if ($like) {
            $em->remove($like);
        } else {
            $like = new Likes();
            $like->setUser($user);
            $cv->addLikes($like);
            $em->persist($like);
        }
        $em->flush();
        return $this->render('cv/_like.html.twig', [
            'cv' => $cv,
            'position' => $position,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_cv_delete_one')]
    public function delete(Position $position, Cv $cv, EntityManagerInterface $entityManager): Response
    {
        $user = $cv->getUser();
        if ($user) {
            $user->removeCv($cv);
        }
        $entityManager->remove($cv);

        $entityManager->flush();

        return $this->redirectToRoute('app_position_show', ['id' => $position->getId()], Response::HTTP_SEE_OTHER);
    }
}
