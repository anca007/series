<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        //$series = $serieRepository->findBy([], ["popularity" => "DESC"], 50);

        $series = $serieRepository->findBestSeries(200);
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
        if (!$serie) {
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }

        return $this->render('serie/show.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(
        EntityManagerInterface $entityManager,
        Request                $request): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //extrait les données de la requête
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash("success", "Serie " . $serie->getName() . " added  ! ");

            return $this->redirectToRoute("serie_show", ['id' => $serie->getId()]);
        }

        //TODO renvoyer un formulaire d'ajout de série
        return $this->render('serie/new.html.twig', [
            "serieForm" => $serieForm->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => '\d+'])]
    public function edit(
        int $id,
        EntityManagerInterface $entityManager,
        SerieRepository $serieRepository,
        Request                $request): Response
    {
        $serie = $serieRepository->find($id);
        $serieForm = $this->createForm(SerieType::class, $serie);

        //extrait les données de la requête
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash("success", "Serie " . $serie->getName() . " updated  ! ");

            return $this->redirectToRoute("serie_show", ['id' => $serie->getId()]);
        }

        //TODO renvoyer un formulaire d'ajout de série
        return $this->render('serie/new.html.twig', [
            "serieForm" => $serieForm->createView()
        ]);
    }

}
