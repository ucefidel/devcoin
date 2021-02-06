<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Controller\DefaultController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends DefaultController
{

    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/login", name="user_login")
     * @return Response
     */
    public function login(): Response
    {
        return $this->render("user/login.html.twig");
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
    }

    /**
     * @Route("/user/new", name="registration_user")
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $this->encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $this->manager->persist($user);
            $this->manager->flush();
        }
        return $this->render("user/registration.html.twig", [
            "form" => $form->createView()
        ]);
    }


}
