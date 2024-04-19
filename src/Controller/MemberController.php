<?php

namespace App\Controller;
use App\Form\MemberMessageType;
use App\Service\MemberService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller handling member requests
 */
class MemberController extends AbstractController
{

    private MemberService $memberService;

    public function __construct(MemberService $memberService) {
        $this->memberService = $memberService;
    }

    /**
     * Renders the form to create a new member message
     *
     * @param int $id The ID of the member
     * @return Response
     */
    #[Route('/course/member/{id}/createMessage', name: 'member_create_message_view', methods: ['GET'])]
    public function createMemberMessageView(int $id): Response {
        $member = $this->memberService->getMember($id);
        $form = $this->createForm(MemberMessageType::class);
        return $this->render('courses/member/createMessage.html.twig', [
            'error' => null,
            'form' => $form,
            'member' => $member->getName(),
        ]);
    }

    /**
     * Renders the form to create a new member message
     *
     * @param int $id The ID of the member
     * @return Response
     */
    #[Route('/course/member/{id}/createMessage', name: 'member_create_message', methods: ['POST'])]
    public function createMemberMessage(int $id, Request $request): Response {
        $member = $this->memberService->getMember($id);
        $form = $this->createForm(MemberMessageType::class);
        $form->handleRequest($request);
        try {
            $this->memberService->createMemberMessage($member, $form);
            return $this->redirectToRoute('course_view', ['id' => $member->getCourse()->getId()]);
        } catch (\Exception $exception) {
            return $this->render('courses/member/createMessage.html.twig', [
                'error' => $exception->getMessage(),
                'form' => $form,
                'member' => $member->getName(),
            ]);
        }

    }
}