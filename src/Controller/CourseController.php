<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CourseController extends AbstractController
{

    #[Route('/admin/courses', name: 'courses_list', methods: ['GET'])]
    public function listView(): Response {
        return $this->render('admin/course/list.html.twig', []);
    }

    #[Route('/admin/courses/{id}', name: 'courses_view', methods: ['GET'])]
    public function listCourse(int $id): Response {
        return $this->render('admin/course/view.html.twig', [
            'id' => $id,
        ]);
    }
}