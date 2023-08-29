<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(): Response
    {
        //TODO renvoyer la liste de toutes les séries
        return $this->render('serie/list.html.twig');
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id): Response
    {
        //TODO renvoyer les informations de la série
        return $this->render('serie/show.html.twig');
    }

    #[Route('/new', name: 'new')]
    public function new(): Response
    {
        //TODO renvoyer un formulaire d'ajout de série
        return $this->render('serie/new.html.twig');
    }

}
