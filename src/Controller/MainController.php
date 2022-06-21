<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'main_')]
class MainController extends AbstractController
{
    #[Route('', name: 'home')]
    public function home(): Response
    {
        $username = "<h1>Sylvain</h1>";
        $serie = ["title" => "Une nounou d'enfer", "year" => 1993, "nbSeasons" => 6];

        return $this->render("main/home.html.twig", [
            "user" => $username,
            "serie" => $serie
        ]);
    }

    #[Route('/test', name: 'test')]
    public function test(): Response
    {

       return $this->render("main/test.html.twig");

    }

}
