<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Handles landing page requests
 */
class LandingController extends AbstractController
{

    /**
     * Default landing page
     *
     * @return Response
     */
    #[Route('/', name: 'landing_page')]
    public function index(): Response {
        return $this->render('landing/index.html.twig', []);
    }

}