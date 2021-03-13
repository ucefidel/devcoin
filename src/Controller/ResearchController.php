<?php


namespace App\Controller;


use App\Entity\HistorySearch;
use App\Entity\User;
use App\Repository\HistorySearchRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchController extends DefaultController
{
    /**
     * @Route("/myresearch",name="myresearch_page")
     * @IsGranted("ROLE_USER")
     * @param HistorySearchRepository $historySearchRepository
     * @return Response
     */
    public function research(HistorySearchRepository $historySearchRepository): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        return $this->render("main/myresearch.html.twig",
            [
                'researchs' => $historySearchRepository->findBy([
                    'user' => $user
                ])
            ]
        );
    }

    /**
     * @Route("/research/{id}/remove", name="research_remove")
     * @param HistorySearch $research
     * @return Response
     */
    public function remove_research(HistorySearch $research): Response
    {
        $this->manager->remove($research);
        $this->manager->flush();

        $this->addFlash('success',
            "La recherche a été supprimée avec succée");

        return $this->redirectToRoute("myresearch_page");
    }
}