<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SerieRepository;
use App\Utils\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeasonController extends AbstractController
{
    #[Route('/season/{serieId}/new', name: 'season_new', requirements: ['serieId' => '\d+'])]
    public function new(
        EntityManagerInterface $entityManager,
        Request $request,
        SerieRepository $serieRepository,
        int $serieId = 0): Response
    {
        $season = new Season();
        if($serieId > 0){
           $serie = $serieRepository->find($serieId);
           $season
               ->setSerie($serie)
               ->setNumber(count($serie->getSeasons()) + 1);
        }

        $seasonForm = $this->createForm(SeasonType::class, $season);
        $seasonForm->handleRequest($request);

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){

            $season->setDateCreated(new \DateTime());
            $entityManager->persist($season);
            $entityManager->flush();

            $this->addFlash("success", "Season added !");
            return $this->redirectToRoute("serie_show", ["id" => $season->getSerie()->getId() ]);
        }

        return $this->render('season/new.html.twig', [
            "seasonForm" => $seasonForm
        ]);
    }
}
