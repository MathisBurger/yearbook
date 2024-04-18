<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateModeratorType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    #[Route('/createModerator', methods: ['GET'])]
    public function createModerator(): Response
    {
        $form = $this->createForm(CreateModeratorType::class);
        return $this->render('moderator/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/createModerator', name: 'create_moderator_form', methods: ['POST'])]
    public function createModeratorPost(Request $request): Response {
        $user = new User();
        $user->setRoles(['ROLE_MODERATOR']);
        $form = $this->createForm(CreateModeratorType::class, $user);
        $form->handleRequest($request);
        echo $form->isSubmitted();
        echo $form->isValid();
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return $this->render('moderator/list.html.twig', [
                'createdModerator' => true
            ]);
        }
        return $this->render('moderator/create.html.twig', [
            'form' => $form
        ]);
    }
}