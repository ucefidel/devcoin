<?php

namespace App\Controller;

use App\Entity\HistorySearch;
use App\Repository\AnnonceRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends DefaultController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('index.html.twig', [
        ]);
    }

    /**
     * @Route("/result",name="global_search")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function search(AnnonceRepository $annonceRepository): Response
    {
        $keyword = $_POST['search'];
        $results = $annonceRepository->findByKeyword($keyword);
        //Save research on DB
        if (count($results) > 0) {
            $research = new HistorySearch();
            $research->setKeyword($keyword);
            $research->setResult(count($results));

            $this->manager->persist($research);
            $this->manager->flush();
        }
        return $this->render("annonce/result_search.html.twig", [
            'results' => $results
        ]);
    }
}
