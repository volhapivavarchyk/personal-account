<?php

declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends AbstractController
{
    /**
     * @Route("/{_locale}", methods="GET|POST", name="index")
     */
    public function show(Request $request): Response
    {
        return $this->redirectToRoute('homepage');
    }
}
