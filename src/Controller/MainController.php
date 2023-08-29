<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(): Response
    {
        $username = "<script>alert('coucou')</script>";
        $serie = ["name" => "NCIS", "genre" => "Crime"];

        return $this->render("main/home.html.twig", [
            "name" => $username,
            "serie" => $serie
        ]);
    }
}
