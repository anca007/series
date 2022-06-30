<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/series', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'retrieve_all', methods: 'GET')]
    public function retrieveAll(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();
       // $json = $serializer->serialize($series, 'json', ['groups' => 'serie_api']);
       return $this->json($series, 200, [], ['groups' => 'serie_api']);
    }

    #[Route('/{id}', name: 'retrieve_one', methods: 'GET')]
    public function retrieveOne(SerieRepository $serieRepository, int $id): Response
    {

        $serie = $serieRepository->find($id);
        return $this->json($serie, 200, [], ['groups' => 'serie_api']);
    }

    #[Route('/{id}', name: 'update_one', methods: 'PUT')]
    public function updateOne(SerializerInterface $serializer,
                              Request $request,
                              SerieRepository $serieRepository,
                              int $id): Response
    {

        $json = $request->getContent();
        $serie = $serializer->deserialize($json, Serie::class, 'json');

        dd($serie);
        $serie = $serieRepository->find($id);
        return $this->json($serie, 200, [], ['groups' => 'serie_api']);
    }

    #[Route('', name: 'new_one', methods: 'POST')]
    public function newOne(SerializerInterface $serializer,
                              Request $request,
                              SerieRepository $serieRepository): Response
    {

        $json = $request->getContent();
        $serie = $serializer->deserialize($json, Serie::class, 'json');

        /**
         * @var Serie $serie
         */
        $serie->setDateCreated(new \DateTime());
        $serieRepository->add($serie, true);
        return $this->json(["ok" => "Serie added"]);
    }


}
