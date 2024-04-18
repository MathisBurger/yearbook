<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\CourseMember;
use App\Form\CourseMemberFormType;
use App\Repository\CourseRepository;
use App\Service\MemberService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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

    #[Route('/admin/member/details/{id}', name: 'member_details')]
    public function memberDetails(int $id): Response
    {
        return $this->render('admin/member/view.html.twig', []);
    }

    /**
     * Creates a new community member view
     *
     * @param int $id The ID of the course
     * @param Request $request The request
     * @return Response
     */
    #[Route('/admin/member/create/{id}', name: 'member_create_view', methods: ['GET'])]
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
    #[Route('/admin/member/create/{id}', name: 'member_create', methods: ['POST'])]
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
    #[Route('/admin/member/delete/{id}', name: 'member_delete', methods: ['POST'])]
    public function deleteMember(int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        $member = $this->entityManager->getRepository(CourseMember::class)->find($id);
        try {
            $this->memberService->deleteMember($id);
        } catch (\Exception $e) {}
        return $this->redirectToRoute('course_view', ['id' => $member->getCourse()->getId()]);
    }

}