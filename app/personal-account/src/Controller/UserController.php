<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/", methods="GET|POST", name="homepage")
     */
    public function action(Request $request): Response
    {
        //return $this->render('default/homepage.html.twig',[]);
        return $this->render('users/user.html.twig',[]);
    }
}
