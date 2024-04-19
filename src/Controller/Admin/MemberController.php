<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\CourseMember;
use App\Entity\MemberMessage;
use App\Form\CourseMemberFormType;
use App\Service\MemberService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Member controller that handles member requests
 */
class MemberController extends AbstractController
{

    private readonly EntityManagerInterface $entityManager;
    private readonly MemberService $memberService;

    public function __construct(EntityManagerInterface $entityManager, MemberService $memberService) {
        $this->entityManager = $entityManager;
        $this->memberService = $memberService;
    }

    /**
     * Gets the details of a member
     *
     * @param int $id The ID of the member
     * @return Response
     */
    #[Route('/admin/member/details/{id}', name: 'admin_member_details')]
    public function memberDetails(int $id): Response
    {
        $member = $this->memberService->getMember($id);
        return $this->render('admin/member/view.html.twig', [
            'name' => $member->getName(),
            'messages' => $member->getMessages()->toArray(),
            'headers' => MemberMessage::getHeaders(),
            'actions' => []
        ]);
    }

    /**
     * Creates a new community member view
     *
     * @param int $id The ID of the course
     * @param Request $request The request
     * @return Response
     */
    #[Route('/admin/member/create/{id}', name: 'admin_member_create_view', methods: ['GET'])]
    public function createMemberView(int $id): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        $course = $this->entityManager->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseMemberFormType::class);
        return $this->render('admin/member/create.html.twig', [
            'form' => $form,
            'error' => null,
            'courseName' => $course->getName()
        ]);
    }

    /**
     * Creates a new community member
     *
     * @param int $id The ID of the course
     * @param Request $request The request
     * @return Response
     */
    #[Route('/admin/member/create/{id}', name: 'admin_member_create', methods: ['POST'])]
    public function createMember(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        $course = $this->entityManager->getRepository(Course::class)->find($id);
        $form = $this->createForm(CourseMemberFormType::class);
        $form->handleRequest($request);
        try {
            $this->memberService->createMember($id, $form);
            return $this->redirectToRoute('course_view', ['id' => $id]);
        } catch (\Exception $e) {
            return $this->render('admin/member/create.html.twig', [
                'form' => $form,
                'error' => $e->getMessage(),
                'courseName' => $course->getName()
            ]);
        }
    }

    /**
     * Deletes a course member
     *
     * @param int $id The ID of the member
     * @param Request $request The request
     * @return Response
     */
    #[Route('/admin/member/delete/{id}', name: 'admin_member_delete', methods: ['POST'])]
    public function deleteMember(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        $member = $this->entityManager->getRepository(CourseMember::class)->find($id);
        try {
            $this->memberService->deleteMember($id);
        } catch (\Exception $e) {}
        return $this->redirectToRoute('admin_course_view', ['id' => $member->getCourse()->getId()]);
    }

}