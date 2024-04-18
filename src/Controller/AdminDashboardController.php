<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Handles all admin dashboard routes
 */
class AdminDashboardController extends AbstractController
{

    /**
     * Handles admin dashboard index
     *
     * @return Response
     */
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response {
        return $this->render('admin/index.html.twig', []);
    }

}