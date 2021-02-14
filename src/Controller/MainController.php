<?php

namespace App\Controller;

use App\Entity\HistorySearch;
use App\Entity\User;
use App\Repository\AnnonceRepository;
use App\Repository\FavorisRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends DefaultController
{
    /**
     * @Route("/", name="main")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function index(AnnonceRepository $annonceRepository): Response
    {
        if (!empty($_POST['keyword']) || !empty($_POST['localisation'])) {
            $keyword = $_POST['keyword'];
            $localisation = $_POST['localisation'];
            $annonces = $annonceRepository->findBySearcher($keyword, $localisation);

            dump($annonces);
            return $this->render("index.html.twig", [
                "result" => $annonces
            ]);
        }

        return $this->render('index.html.twig', [
            "annonces" => $annonceRepository->findAll()
        ]);

    }

    /**
     * @Route("/result",name="global_search")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function search(AnnonceRepository $annonceRepository): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        dump($user);
        $keyword = $_POST['search'];
        $results = $annonceRepository->findBySearcher($keyword);
        //Save research on DB
        if (count($results) > 0) {
            $research = new HistorySearch();
            $research->setKeyword($keyword);
            $research->setResult(count($results));
            if ($user !== null) {
                $research->setUser($user);
            }

            $this->manager->persist($research);
            $this->manager->flush();
        }
        return $this->render("annonce/result_search.html.twig", [
            'results' => $results
        ]);
    }

    /**
     * @Route("favoris/all" , name="favoris_name")
     * @IsGranted("ROLE_USER")
     * @param FavorisRepository $favorisRepo
     * @return Response
     */
    public function favoris(FavorisRepository $favorisRepo): Response
    {
        return $this->render('annonce/favoris.html.twig', [
            "favoris" => $favorisRepo->findAll()
        ]);
    }
}
