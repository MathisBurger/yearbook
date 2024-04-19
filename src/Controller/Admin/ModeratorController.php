<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\CreateModeratorType;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/admin/createModerator', name: 'admin_create_moderator_view', methods: ['GET'])]
    public function createModerator(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
    #[Route('/admin/createModerator', name: 'admin_create_moderator_form', methods: ['POST'])]
    public function createModeratorPost(Request $request): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = new User();
        $user->setRoles(['ROLE_MODERATOR']);
        $form = $this->createForm(CreateModeratorType::class, $user);
        $form->handleRequest($request);
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
                return $this->redirectToRoute('admin_moderator_list');
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
    #[Route('/admin/moderatorList', name: 'admin_moderator_list', methods: ['GET'])]
    public function listModerators(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/moderator/list.html.twig', [
            'headers' => User::getHeaders(),
            'moderators' => $this->entityManager->getRepository(User::class)->findAll(),
            'actions' => [
                ['class' => 'btn-danger', 'label' => 'Delete', 'basePath' => '/admin/moderator/delete'],
                ['class' => 'btn-primary', 'label' => 'Elevate', 'basePath' => '/admin/moderator/elevatePermissions']
            ]
        ]);
    }

    /**
     * Deletes a specific user
     *
     * @param int $id The ID of the user
     * @return Response
     */
    #[Route('/admin/moderator/delete/{id}', name: 'admin_moderator_delete', methods: ['POST'])]
    public function deleteModerator(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $moderator = $this->entityManager->getRepository(User::class)->find($id);
        $this->entityManager->remove($moderator);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_moderator_list');
    }

    /**
     * Elevates user permissions
     *
     * @param int $id The ID of the user
     * @return Response
     */
    #[Route('/admin/moderator/elevatePermissions/{id}', name: 'admin_moderator_elevate', methods: ['POST'])]
    public function elevatePermissions(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $moderator = $this->entityManager->getRepository(User::class)->find($id);
        $moderator->setRoles(['ROLE_ADMIN']);
        $this->entityManager->persist($moderator);
        $this->entityManager->flush();
        return $this->redirectToRoute('admin_moderator_list');
    }
}