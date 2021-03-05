<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordConfirmType;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class UserController extends DefaultController
{

    /**
     * @Route("/user", name="user_page")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
        ]);
    }

    /**
     * @Route("/login", name="user_login")
     * @param AuthenticationUtils $utils
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();
        return $this->render("user/login.html.twig",
            [
                "hasError" => $error,
                "username" => $username
            ]);
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logout()
    {
        // Doing anythings
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

    /**
     * @Route("user/edit/{id}", name="user_update")
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($user);
            $this->manager->flush();
        }

        return $this->render("user/edit.html.twig"
            , [
                "form" => $form->createView()
            ]);
    }


    /**
     * @Route("user/reset_password/{id}", name="user_reset_password")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function password(Request $request, User $user): Response
    {
        $form = $this->createForm(PasswordConfirmType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $hash = $this->encoder->encodePassword($user, $form->getViewData()->getNewPassword());
            $user->setPassword($hash);

            $this->manager->persist($user);
            $this->manager->flush();

            return $this->redirectToRoute('user_login');
        }

        return $this->render("user/reset_password.html.twig", [
            "form" => $form->createView()
        ]);
    }

}
