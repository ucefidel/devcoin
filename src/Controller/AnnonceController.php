<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Controller\DefaultController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class AnnonceController extends DefaultController
{

    /**
     * @Route("/annonces", name="annonces_page")
     */
    public function index(): Response
    {
        return $this->render('annonce/index.html.twig', [
        ]);
    }

    /**
     * @Route("/annonce/new", name="annonce_new")
     * @param Request $request
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function new(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $annonce->setUser($user);
            $this->manager->persist($annonce);
            $this->manager->flush();
        }
        return $this->render("annonce/new.html.twig",
            [
                "form" => $form->createView()
            ]);
    }
}
