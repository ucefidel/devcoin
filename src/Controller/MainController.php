<?php

namespace App\Controller;

use App\Entity\HistorySearch;
use App\Entity\User;
use App\Repository\AnnonceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        /** @var User $user */
        $user = $this->security->getUser();
        $keyword = $_POST['search'];
        $results = $annonceRepository->findByKeyword($keyword);
        //Save research on DB
        if (count($results) > 0) {
            $research = new HistorySearch();
            $research->setKeyword($keyword);
            $research->setResult(count($results));
            if (!empty($user))
                $research->setUser($user);
            $this->manager->persist($research);
            $this->manager->flush();
        }
        return $this->render("annonce/result_search.html.twig", [
            'results' => $results
        ]);
    }

    /**
     * @Route("/myresearch",name="myresearch_page")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myresearch(): Response
    {

        return $this->render("main/myresearch.html.twig");
    }
}
