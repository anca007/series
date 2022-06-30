<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{

    #[Route('/list/{page}', name: 'list')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

        $nbMaxSeries = $serieRepository->count([]);
        $maxPage = ceil($nbMaxSeries / 50);

        if ($page < 1 || $page > $maxPage) {
            throw $this->createNotFoundException("This page doesn't exist !");
        }

        //récupère toutes les series
        //$series = $serieRepository->findAll();
        //récupère les séries populaires
        $series = $serieRepository->findBestSeries($page);

        //récupère toutes les séries et les trie par popularité
        //$series = $serieRepository->findBy([], ['popularity' => 'DESC']);

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
        if (!$serie) {
            throw $this->createNotFoundException("Oops ! Serie doesn't exist !");
        }

        return $this->render('serie/detail.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, SerieRepository $serieRepository, ImageUpload $imageUpload): Response
    {
        //TODO permettre la création d'une nouvelle série
        $serie = new Serie();

        $serieForm = $this->createForm(SerieType::class, $serie);

        //associe la requête au formulaire
        $serieForm->handleRequest($request);

        //teste la soumission du formulaire
        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            //ajout date de création sinon erreur à l'insertion
            $serie->setDateCreated(new \DateTime());

            /**
             * @var UploadedFile $file
             */
            $file = $serieForm->get('poster')->getData();

            //appel à notre service d'upload
            $newFileName = $imageUpload->save($file, $serie->getName(), $this->getParameter('upload_posters_serie_dir'));

            $serie->setPoster($newFileName);

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

    #[Route('/edit/{id}', name: 'edit', requirements: ["id" => "\d+"])]
    public function edit(int $id, SerieRepository $serieRepository, Request $request): Response
    {
        //récupération de la série en bdd en focntion de l'id
        $serie = $serieRepository->find($id);

        //si je ne récupère pas de série je lance une erreur 404
        if (!$serie) {
            throw $this->createNotFoundException("Oops ! Serie doesn't exist !");
        }

        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $serie->setDateModified(new \DateTime());
            $serieRepository->add($serie, true);

            $this->addFlash("success", "Serie edited !");
            return $this->redirectToRoute("serie_detail", ['id' => $serie->getId()]);
        }


        return $this->render('serie/edit.html.twig', [
            "serieForm" => $serieForm->createView(),

        ]);
    }


    #[Route('/delete/{id}', name: 'delete', requirements: ["id" => "\d+"])]
    public function delete(int $id, SerieRepository $serieRepository): Response
    {
        //récupération de la série en bdd en fonction de l'id
        $serie = $serieRepository->find($id);

        //si je ne récupère pas de série je lance une erreur 404
        if (!$serie) {
            throw $this->createNotFoundException("Oops ! Serie doesn't exist !");
        }


        //faire la suppression
        $serieRepository->remove($serie, true);

        $this->addFlash('success', "Serie removed !");

        return $this->redirectToRoute('serie_list');
    }


}
