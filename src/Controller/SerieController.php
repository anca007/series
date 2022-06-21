<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{

    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        //TODO renvoyer la liste des séries
        return $this->render('serie/list.html.twig');
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ["id" => "\d+"])]
    public function detail(int $id): Response
    {
        //TODO renvoyer le détail de la série choisie
        dump($id);
        return $this->render('serie/detail.html.twig');
    }

    #[Route('/create', name: 'create')]
    public function create(): Response
    {
        //TODO permettre la création d'une nouvelle série

        return $this->render('serie/create.html.twig');
    }
}
