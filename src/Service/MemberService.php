<?php

namespace App\Service;

use App\Entity\CourseMember;
use App\Repository\CourseMemberRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

/**
 * Service handling member transactions
 */
class MemberService
{

    private readonly EntityManagerInterface $entityManager;
    private readonly CourseRepository $courseRepository;
    private readonly CourseMemberRepository $courseMemberRepository;

    public function __construct(EntityManagerInterface $entityManager, CourseRepository $courseRepository, CourseMemberRepository $courseMemberRepository) {
        $this->entityManager = $entityManager;
        $this->courseRepository = $courseRepository;
        $this->courseMemberRepository = $courseMemberRepository;
    }

    /**
     * Creates a new member
     *
     * @param int $id The ID of the course
     * @param FormInterface $form The form
     * @return void
     */
    public function createMember(int $id, FormInterface $form)
    {
        $course = $this->courseRepository->find($id);
        if ($form->isSubmitted() && $form->isValid() && $course !== null) {
            $data = $form->getData();
            if ($data instanceof CourseMember) {
                $data->setCourse($course);
                $course->addMember($data);
                $this->entityManager->persist($data);
                $this->entityManager->persist($course);
                $this->entityManager->flush();
                return;
            }
        }
        throw new BadRequestException();
    }

    /**
     * Deletes a member
     *
     * @param int $id The ID of the member
     * @return void
     */
    public function deleteMember(int $id)
    {
        $course = $this->courseMemberRepository->find($id);
        if ($course !== null) {
            $this->entityManager->remove($course);
            $this->entityManager->flush();
            return;
        }
        throw new BadRequestException();
    }
}