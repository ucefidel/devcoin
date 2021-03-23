<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\HistorySearch;
use App\Entity\User;
use App\Form\HistorySearchType;
use App\Repository\AnnonceRepository;
use App\Repository\FavorisRepository;
use App\Service\EmailService;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swift_Mailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends DefaultController
{


    /**
     * @Route("/", name="main")
     * @param AnnonceRepository $annonceRepository
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function index(AnnonceRepository $annonceRepository, PaginatorInterface $paginator, Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $history_search = new HistorySearch();
        $form = $this->createForm(HistorySearchType::class, $history_search);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $keyword = $history_search->getKeyword();

            try {
                $localization = $history_search->getLocalization();
            } catch (Exception $e) {
                $localization = "";
            }

            dump($localization);
            $history_search->setUser($user);

            $annonces = $annonceRepository->findBySearcher($keyword, $localization);

            $history_search->setResult("" . count($annonces));
            $this->manager->persist($history_search);
            $this->manager->flush();

            return $this->render("index.html.twig", [
                "form" => $form->createView(),
                "result" => $annonces
            ]);
        }

        $donnes = $annonceRepository->findAll();
        $annonces = $paginator->paginate(
            $donnes,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('index.html.twig', [
            "form" => $form->createView(),
            "annonces" => $annonces
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
     * @Route("favorisall" , name="favoris_name")
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

    /**
     * @Route("/send/{id}/email", name="send_email")
     * @IsGranted("ROLE_USER")
     * @param Annonce $annonce
     * @param MailerInterface $mailer
     * @param AnnonceRepository $annonceRepository
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function send(Annonce $annonce, MailerInterface $mailer, AnnonceRepository $annonceRepository): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $advertiser = $annonceRepository->findEmailOfAds($annonce->getId());

        $subject = $_POST['subjectEmail'];
        $message = $_POST['messageEmail'];

        dump($advertiser);
        
        $message = (new Email())
            ->from($user->getEmail())
            ->to($advertiser)
            ->subject($subject)
            ->text(
                $message
            );

        $mailer->send($message);

        return $this->redirectToRoute("main");
    }


}
