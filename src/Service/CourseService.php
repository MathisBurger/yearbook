<?php

namespace App\Service;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Service handling everything about courses
 */
class CourseService
{

    private CourseRepository $courseRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CourseRepository $courseRepository, EntityManagerInterface $entityManager) {
        $this->courseRepository = $courseRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Creates a new course
     *
     * @param Form $form The form instance
     * @return Course The new course
     */
    public function createCourse(FormInterface $form): Course
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data instanceof Course) {
                $this->entityManager->persist($data);
                $this->entityManager->flush();
                return $data;
            }
        }
        throw new BadRequestException("Unable to create course");
    }

    /**
     * Deletes a course
     *
     * @param int $id The ID of the course
     */
    public function deleteCourse(int $id)
    {
        $course = $this->courseRepository->find($id);
        if (null === $course) {
            throw new NotFoundHttpException("Course not found");
        }
        $this->entityManager->remove($course);
        $this->entityManager->flush();
    }

}