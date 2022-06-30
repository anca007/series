<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\Serie;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/new/{id}', name: 'new')]
    public function new(
        Request $request,
        SeasonRepository $seasonRepository,
        SerieRepository $serieRepository,
        int $id = 0): Response
    {
        //créer instance saison
        $season = new Season();

        if($id > 0){
            //récupération de la série en bdd
            $serie = $serieRepository->find($id);

            if($serie){
                //je sette la série à la saison
                $season->setSerie($serie);
            }else{
                //je lance une 404
                throw $this->createNotFoundException("I can't add the season to the serie");
            }
        }

        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){


            $season->setDateCreated(new \DateTime());
            $seasonRepository->add($season, true);

            //feedBack user
            $this->addFlash("success", "Season added to ". $season->getSerie()->getName());

            //redirection vers page détail
            return $this->redirectToRoute('serie_detail', ['id' => $season->getSerie()->getId()]);

        }

        return $this->render('season/new.html.twig', [
                'seasonForm' => $seasonForm->createView()
        ]);
    }
}
