<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\CourseMember;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * The course controller that handles course requests
 */
class CourseController extends AbstractController
{

    private CourseRepository $courseRepository;

    public function __construct(CourseRepository $courseRepository) {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Renders a list of all courses
     *
     * @return Response
     */
    #[Route("/courses", name: "courses_view", methods: ['GET'])]
    public function listCoursesView(): Response
    {
        return $this->render('courses/list.html.twig', [
            'headers' => Course::getHeaders(),
            'courses' => $this->courseRepository->findAll(),
            'actions' => [
                ['class' => 'btn-primary', 'label' => 'View', 'linkPath' => 'course_view']
            ]
        ]);
    }

    /**
     * Creates a detailed course view
     *
     * @param int $id The ID of the course
     * @return Response
     */
    #[Route("/courses/details/{id}", name: "course_view", methods: ['GET'])]
    public function courseView(int $id): Response
    {
        $course = $this->courseRepository->find($id);
        return $this->render('courses/view.html.twig', [
            'name' => $course->getName(),
            'id' => $course->getId(),
            'headers' => CourseMember::getHeaders(),
            'students' => $course->getMembers()->filter(fn (CourseMember $member) => $member->getRole() === CourseMember::ROLE_STUDENT)->toArray(),
            'professors' => $course->getMembers()->filter(fn (CourseMember $member) => $member->getRole() === CourseMember::ROLE_PROFESSOR)->toArray(),
            'actions' => [
                ['class' => 'btn-primary', 'label' => 'Create message'],
            ]
        ]);
    }

}