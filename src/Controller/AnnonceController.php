<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @IsGranted("ROLE_USER")
     * @param Request $request
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

    /**
     * @Route("annonce/all", name="annonce_show_all")
     * @IsGranted("ROLE_USER")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function showAll(AnnonceRepository $annonceRepository): Response
    {
        return $this->render("annonce/shows_all.html.twig",
            [
                "annonces" => $annonceRepository->findAll()
            ]);
    }

    /**
     * @Route("/annonce/edit/{id}", name="annonce_update")
     * @param Annonce $annonce
     * @param Request $request
     * @return Response
     */
    public function edit(Annonce $annonce, Request $request): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($annonce);
            $this->manager->flush();
        }

        return $this->render("annonce/edit.html.twig",
            [
                "form" => $form->createView()
            ]);
    }

    /**
     * @Route("annonce/delete/{id}", name="annonce_delete")
     * @param Annonce $annonce
     * @return Response
     */
    public function delete(Annonce $annonce): Response
    {
        $this->manager->remove($annonce);
        $this->manager->flush();

        $this->addFlash(
            'success',
            "L'annonce a bien été enregistrée !"
        );

        return $this->redirectToRoute("annonce_show_all");
    }

}
