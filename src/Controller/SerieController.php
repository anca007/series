<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //récupération de toutes les séries
        //$series = $serieRepository->findAll();

        $series = $serieRepository->findBy([], ["popularity" => "DESC"], 50);
        dump($series);

        return $this->render('serie/list.html.twig', [
            "series" => $series
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        dump($id);

        $serie = $serieRepository->find($id);

        //si la série n'est pas trouvé je renvoie une 404
        if(!$serie){
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }

        return $this->render('serie/show.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(EntityManagerInterface $entityManager): Response
    {

        $serie = new Serie();
        $serie
            ->setBackdrop("backdrop")
            ->setDateCreated(new \DateTime())
            ->setGenres("SF")
            ->setName("X-Files")
            ->setFirstAirDate(new \DateTime("-10 year"))
            ->setPopularity(500)
            ->setPoster('poster.png')
            ->setStatus('ending')
            ->setTmdbId(1234)
            ->setVote(8);

        dump($serie);

        $entityManager->persist($serie);
        $entityManager->flush();

        //update du nom de la série
        $serie->setName("Code Quantum");
        dump($serie);
        //ça lance un update et non une nouvelle insertion
        $entityManager->persist($serie);
        $entityManager->flush();

        dump($serie);

        $entityManager->remove($serie);
        $entityManager->flush();

        dump($serie);

        //TODO renvoyer un formulaire d'ajout de série
        return $this->render('serie/new.html.twig');
    }

}
