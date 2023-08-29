<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TotoController extends AbstractController
{
    #[Route('/toto', name: 'app_toto')]
    public function index(): Response
    {
        return $this->render('toto/index.html.twig', [
            'controller_name' => 'TotoController',
        ]);
    }
}
