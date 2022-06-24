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

    #[Route('/list/{page}', name: 'list')]
    public function list(int $page = 1, SerieRepository $serieRepository): Response
    {

        $nbMaxSeries = $serieRepository->count([]);
        $maxPage = ceil($nbMaxSeries / 50);

        if($page < 1 || $page > $maxPage){
            throw $this->createNotFoundException("This page doesn't exist !");
        }

        //récupère toutes les series
        //$series = $serieRepository->findAll();
        //récupère les séries populaires
        $series = $serieRepository->findBestSeries($page);

        //récupère toutes les séries et les trie par popularité
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC']);

        dump($series);

        return $this->render('serie/list.html.twig', [
            "series" => $series,
            "currentPage" => $page,
            "maxPage" => $maxPage
        ]);
    }

    #[Route('/detail/{id}', name: 'detail', requirements: ["id" => "\d+"])]
    public function detail(int $id, SerieRepository $serieRepository): Response
    {
        //récupération de la série en bdd en focntion de l'id
        $serie = $serieRepository->find($id);

        //si je ne récupère pas de série je lance une erreur 404
        if(!$serie){
            throw $this->createNotFoundException("Oops ! Serie doesn't exist !");
        }

        return $this->render('serie/detail.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, SerieRepository $serieRepository): Response
    {
        //TODO permettre la création d'une nouvelle série
        $serie = new Serie();
        $serie->setName("Code Quantum");
        $serieForm = $this->createForm(SerieType::class, $serie);


        //associe la requête au formulaire
        $serieForm->handleRequest($request);

        //teste la soumission du formulaire
        if($serieForm->isSubmitted() && $serieForm->isValid()){

            //ajout date de création sinon erreur à l'insertion
            $serie->setDateCreated(new \DateTime());

            //enregistrement de le série en BDD
            $serieRepository->add($serie, true);

            //feedback utilisateur
            $this->addFlash("success", "Serie added !");

            //redirection vers la page de détail de la série créée
            return $this->redirectToRoute('serie_detail', ['id' => $serie->getId()]);
        }

        return $this->render('serie/create.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }
}
