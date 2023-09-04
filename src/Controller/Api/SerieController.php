<?php

namespace App\Controller\Api;

use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/api/serie', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
        return $this->json($series, 200, [], ['groups' => 'serie_api']);
    }
    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(): Response
    {

    }
    #[Route('', name: 'add', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function add(): Response
    {

    }

    #[Route('/{id}', name: 'update', requirements: ['id' => '\d+'], methods: ['PUT', 'PATCH'])]
    public function update(): Response
    {

    }
    #[Route('/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function delete(): Response
    {

    }
}
