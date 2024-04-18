<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateModeratorType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Handles all user interactions
 */
class ModeratorController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher) {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * Gets the moderator creation form view
     *
     * @return Response
     */
    #[Route('/admin/createModerator', name: 'create_moderator_view', methods: ['GET'])]
    public function createModerator(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new Response('Access denied', Response::HTTP_FORBIDDEN);
        }
        $form = $this->createForm(CreateModeratorType::class);
        return $this->render('admin/moderator/create.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Creates a moderator
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/admin/createModerator', name: 'create_moderator_form', methods: ['POST'])]
    public function createModeratorPost(Request $request): Response {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new Response('Access denied', Response::HTTP_FORBIDDEN);
        }
        $user = new User();
        $user->setRoles(['ROLE_MODERATOR']);
        $form = $this->createForm(CreateModeratorType::class, $user);
        $form->handleRequest($request);
        echo $form->isSubmitted();
        echo $form->isValid();
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data instanceof User) {
                $user = $data;
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $user->getPassword()
                    )
                );
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $this->redirectToRoute('moderator_list');
            }
        }
        return $this->render('admin/moderator/create.html.twig', [
            'form' => $form,
            'error' => 'Error during moderator creation'
        ]);
    }

    /**
     * Lists all moderators
     *
     * @return Response
     */
    #[Route('/admin/moderatorList', name: 'moderator_list', methods: ['GET'])]
    public function listModerators(): Response
    {
        return $this->render('admin/moderator/list.html.twig', []);
    }
}