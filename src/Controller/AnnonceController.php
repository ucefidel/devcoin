<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Favoris;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Repository\FavorisRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AnnonceController
 * @package App\Controller
 * @Route("/annonces")
 */
class AnnonceController extends DefaultController
{
    /**
     * @Route("/", name="annonces_page")
     */
    public function index(): Response
    {
        return $this->render('annonce/index.html.twig', [
        ]);
    }

    /**
     * @Route("/new", name="annonce_new")
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

            // Upload file Image ----------
            /** @var UploadedFile $fileName */
            $fileName = $form->get('picture')->getData();

            if ($fileName) {
                $originalFilename = pathinfo($fileName->getClientOriginalExtension(), PATHINFO_FILENAME);
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $fileName->guessExtension();

                try {
                    $fileName->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );

                } catch (FileException $e) {
                }

                $annonce->setPicture($newFilename);
            }
            // ----------------------------
            $annonce->setUser($user);
            $this->manager->persist($annonce);
            $this->manager->flush();

            $this->addFlash(
                'success',
                'Votre annonce est ajouté avec succée'
            );

            return $this->redirectToRoute('annonce_show_all');
        }
        return $this->render("annonce/new.html.twig",
            [
                "form" => $form->createView()
            ]);
    }

    /**
     * @Route("/all", name="annonce_show_all")
     * @IsGranted("ROLE_USER")
     * @param AnnonceRepository $annonceRepository
     * @return Response
     */
    public function showAll(AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findByUser($this->security->getUser()->getId());
        return $this->render("annonce/shows_all.html.twig",
            [
                "annonces" => $annonces
            ]);
    }

    /**
     * @Route("/edit/{id}", name="annonce_update")
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

            $this->addFlash(
                'success',
                'Annonce modifié avec succée'
            );

            return $this->redirectToRoute("annonce_show_all");
        }

        return $this->render("annonce/edit.html.twig",
            [
                "form" => $form->createView()
            ]);
    }

    /**
     * @Route("/delete/{id}", name="annonce_delete")
     * @param Annonce $annonce
     * @return Response
     */
    public function delete(Annonce $annonce): Response
    {
        $this->manager->remove($annonce);
        $this->manager->flush();

        $this->addFlash(
            'success',
            "L'annonce a bien été supprimée !"
        );

        return $this->redirectToRoute("annonce_show_all");
    }

    /**
     * @Route("/show/{id}", name="show_annonce")
     * @param Annonce $annonce
     * @param AnnonceRepository $annonceRepo
     * @return Response
     */
    public function show(Annonce $annonce, AnnonceRepository $annonceRepo): Response
    {
        return $this->render('annonce/show.html.twig',
            [
                'annonce' => $annonceRepo->find($annonce->getId())
            ]);
    }

    /**
     * @Route("/{id}/favoris", name="annonce_favoris")
     * @param Annonce $annonce
     * @param FavorisRepository $favorisRepo
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function favoris(Annonce $annonce, FavorisRepository $favorisRepo): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            return $this->json([
                "code" => 403,
                "message" => "Unauthorized"
            ], 403);
        }

        if ($annonce->isFavorites($user)) {
            $favoris = $favorisRepo->findOneBy([
                "annonce" => $annonce,
                "user" => $user
            ]);
            $this->manager->remove($favoris);
            $this->manager->flush();

            return $this->json([
                "code" => 200,
                "message" => "Favoris bien supprimée"
            ], 200);
        }

        $favoris = new Favoris();
        $favoris->setUser($user)
            ->setAnnonce($annonce);
        $this->manager->persist($favoris);
        $this->manager->flush();

        return $this->json([
            'code' => 200, '
            message' => "Favoris bien enregistré"
        ], 200);
    }

    /**
     * @Route("/export", name="annonce_export")
     * @return Response
     */
    public function export(): Response
    {
        return new Response();
    }
}
