<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        var_dump($lastUsername);
        var_dump($this->isGranted('IS_AUTHENTICATED_REMEMBERED'));
        var_dump($this->isGranted('IS_AUTHENTICATED_FULLY'));
        return $this->render(
            'security/security.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(Request $request, AuthenticationUtils $authUtils): Response
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}

