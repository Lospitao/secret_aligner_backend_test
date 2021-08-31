<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public const USER_TODOS = 'user_todos';
    /**
     * @Route("/login", name="app_login")
     */
    public function login(UrlGeneratorInterface $urlGenerator, AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isUserAlreadyLoggedIn()) {
            $userId = $this->getUser()->getId();
            return new RedirectResponse($urlGenerator->generate(self::USER_TODOS, ['userId' =>$userId]));
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    private function isUserAlreadyLoggedIn()
    {
        return $this->getUser();
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

        $this->redirectToRoute('app_login');
    }


}
