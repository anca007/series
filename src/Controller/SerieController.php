<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{

    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //TODO renvoyer la liste des séries

        //$series = $serieRepository->findAll();
        //$series = $serieRepository->findBestSeries();
        $series = $serieRepository->findBy([], ['vote' => 'DESC'], 50);

        dump($series);

        return $this->render('serie/list.html.twig', [
            "series" => $series
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ["id" => "\d+"])]
    public function detail(int $id, SerieRepository $serieRepository): Response
    {
        //TODO renvoyer le détail de la série choisie
        $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException("Oops ! Serie doesn't exist !");
        }

        return $this->render('serie/detail.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $entityManager, SerieRepository $serieRepository): Response
    {
        //TODO permettre la création d'une nouvelle série

        $serie = new Serie();

        $serie->setBackdrop("backdrop")
            ->setDateCreated(new \DateTime())
            ->setFirstAirDate(new \DateTime())
            ->setGenres("SF")
            ->setLastAirDate(new \DateTime())
            ->setName("Code Quantum")
            ->setPopularity(250.15)
            ->setVote(8.5)
            ->setPoster("Poster")
            ->setStatus("Canceled")
            ->setTmdbId(123456);

        $serieRepository->add($serie, true);

//        dump($serie);
//
//        $entityManager->persist($serie);
//        $entityManager->flush();
//
//        dump($serie);
//
//        $entityManager->remove($serie);
//        $entityManager->flush();
//
//        dump($serie);


        return $this->render('serie/create.html.twig');
    }
}
