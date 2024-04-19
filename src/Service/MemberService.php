<?php

namespace App\Service;

use App\Entity\CourseMember;
use App\Entity\MemberMessage;
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
     * Gets a course member by ID
     *
     * @param int $id The ID of the member
     * @return CourseMember
     */
    public function getMember(int $id): CourseMember
    {
        return $this->courseMemberRepository->find($id);
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

    /**
     * Creates a new member message
     *
     * @param CourseMember $member The course member
     * @param FormInterface $form The form containing the data
     * @return void
     */
    public function createMemberMessage(CourseMember $member, FormInterface $form) {
        $message = new MemberMessage();
        if ($form->isSubmitted() && $form->isValid() && $message !== null) {
            $data = $form->getData();
            if ($data instanceof MemberMessage) {
                $message = $data;
                $message->setMember($member);
                $this->entityManager->persist($message);
                $member->addMessage($message);
                $this->entityManager->persist($message);
                $this->entityManager->flush();
                return;
            }
        }
        throw new BadRequestException("Invalid data");
    }
}