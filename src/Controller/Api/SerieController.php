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
                              Request             $request,
                              SerieRepository     $serieRepository,
                              int                 $id): Response
    {

        $json = $request->getContent();
        $serie = $serializer->deserialize($json, Serie::class, 'json');

        //TODO à terminer
//        $serie = $serieRepository->find($id);
//        return $this->json($serie, 200, [], ['groups' => 'serie_api']);
    }

    #[Route('', name: 'new_one', methods: 'POST')]
    public function newOne(SerializerInterface $serializer,
                           Request             $request,
                           SerieRepository     $serieRepository): Response
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

    #[Route('/update-like', name: 'update_like', methods: 'POST')]
    public function updateLike(Request $request, SerieRepository $serieRepository){

        $json = json_decode($request->getContent());

        $serie = $serieRepository->find($json->serieId);

        //met à jour l'attribut like en fonction du bouton cliquer
        if($json->like){
            $serie->setNbLikes($serie->getNbLikes() + 1);
        }else{
            $serie->setNbLikes($serie->getNbLikes() - 1);
        }

        //mets à jour la série en BDD
        $serieRepository->add($serie, true);

       return $this->json(["nbLike" => $serie->getNbLikes()]);

    }






}
