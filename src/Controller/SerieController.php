<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{

    #[Route('/list/{page}', name: 'list')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //récupération de toutes les séries
        //$series = $serieRepository->findAll();
        //$series = $serieRepository->findBy([], ["popularity" => "DESC"], 50);

        if ($page < 1) {
            $page = 1;
        }

        $totalSeries = $serieRepository->count([]);
        $maxPage = ceil($totalSeries / 50);

        if ($page > $maxPage) {
            $page = $maxPage;
        }

        //$series = $serieRepository->findBestSeries(200);
        if ($page <= $maxPage) {
            $series = $serieRepository->findSeriesWithPagination($page);
        } else {
            throw $this->createNotFoundException("Page not found !");
        }

        return $this->render('serie/list.html.twig', [
            "series" => $series,
            "currentPage" => $page,
            "maxPage" => $maxPage
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
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
//    #[IsGranted("ROLE_USER")]
    public function new(
        EntityManagerInterface $entityManager,
        Request                $request,
        Uploader               $uploader
    ): Response
    {
        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);

        //extrait les données de la requête
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $serie->setPoster(
                $uploader->upload($serieForm->get('poster')->getData(),
                    $this->getParameter('upload_poster_serie_dir'),
                    $serie->getName()));
            $serie->setBackdrop(
                $uploader->upload($serieForm->get('backdrop')->getData(),
                    $this->getParameter('upload_backdrop_serie_dir'),
                    $serie->getName()));

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
        int                    $id,
        EntityManagerInterface $entityManager,
        SerieRepository        $serieRepository,
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

    #[Route('/{id}/delete', name: 'delete', requirements: ['id' => '\d+'])]
    #[IsGranted("SERIE_DELETE", "serie", "You can't delete this serie")]
    public function delete(
        Serie                  $serie,
        EntityManagerInterface $entityManager,
        SerieRepository        $serieRepository)
    {

        //$serie = $serieRepository->find($id);

        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'Serie ' . $serie->getName() . ' deleted !');
        return $this->redirectToRoute("serie_list");
    }

}
