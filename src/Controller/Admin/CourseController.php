<?php

namespace App\Controller\Admin;

use App\Entity\Course;
use App\Entity\CourseMember;
use App\Form\CourseType;
use App\Service\CourseService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Course controller handling requests
 */
class CourseController extends AbstractController
{
    private CourseService $courseService;
    private EntityManagerInterface $entityManager;

    public function __construct(CourseService $courseService, EntityManagerInterface $entityManager) {
        $this->courseService = $courseService;
        $this->entityManager = $entityManager;
    }

    /**
     * Lists all courses
     *
     * @return Response
     */
    #[Route('/admin/courses', name: 'admin_courses_list', methods: ['GET'])]
    public function listView(): Response {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        return $this->render('admin/course/list.html.twig', [
            'headers' => Course::getHeaders(),
            'courses' => $this->entityManager->getRepository(Course::class)->findAll(),
            'actions' => [
                ['class' => 'btn-primary', 'label' => 'View', 'linkPath' => 'admin_course_view'],
                ['class' => 'btn-danger', 'label' => 'Delete', 'basePath' => '/admin/courses/delete']
            ]
        ]);
    }

    /**
     * Creates a view for the course
     *
     * @param int $id The ID of the course
     * @return Response
     */
    #[Route('/admin/courses/details/{id}', name: 'admin_course_view', methods: ['GET'])]
    public function courseView(int $id): Response {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        $course = $this->entityManager->getRepository(Course::class)->find($id);
        return $this->render('admin/course/view.html.twig', [
            'name' => $course->getName(),
            'id' => $course->getId(),
            'headers' => CourseMember::getHeaders(),
            'students' => $course->getMembers()->filter(fn (CourseMember $member) => $member->getRole() === CourseMember::ROLE_STUDENT)->toArray(),
            'professors' => $course->getMembers()->filter(fn (CourseMember $member) => $member->getRole() === CourseMember::ROLE_PROFESSOR)->toArray(),
            'actions' => [
                ['class' => 'btn-primary', 'label' => 'View', 'linkPath' => 'admin_member_details'],
                ['class' => 'btn-danger', 'label' => 'Delete', 'basePath' => '/admin/member/delete']
            ]
        ]);
    }

    /**
     * Creates view for a new course
     *
     * @return Response
     */
    #[Route('/admin/courses/new', name: 'admin_courses_new_view', methods: ['GET'])]
    public function createCourseNewView(): Response {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        return $this->render('admin/course/create.html.twig', [
            'form' => $this->createForm(CourseType::class),
            'error' => null,
        ]);
    }

    /**
     * Creates a new course
     *
     * @param Request $request The request to handle
     * @return Response
     */
    #[Route('/admin/courses/new', name: 'admin_courses_new', methods: ['POST'])]
    public function createCourseRequest(Request $request): Response {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        try {
            $this->courseService->createCourse($form);
        } catch (BadRequestException $badRequestException) {
            return $this->render('admin/course/create.html.twig', [
                'form' => $form->createView(),
                'error' => $badRequestException->getMessage(),
            ]);
        }
        return $this->redirectToRoute('admin_courses_list');
    }

    /**
     * Deletes a course
     *
     * @param int $id The course that should be deleted
     * @return Response
     */
    #[Route('/admin/courses/delete/{id}', name: 'admin_courses_delete', methods: ['POST'])]
    public function deleteCourseRequest(int $id): Response {
        $this->denyAccessUnlessGranted('ROLE_MODERATOR');
        try {
            $this->courseService->deleteCourse($id);
        } catch (BadRequestException $badRequestException) {}
        return $this->redirectToRoute('admin_courses_list');
    }

}