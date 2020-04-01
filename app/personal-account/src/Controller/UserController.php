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
     * @Route("/personal/{module}/{service}", methods="GET|POST", name="homepage")
     */
    public function action(Request $request, int $module = 0, int $service = 0): Response
    {
        var_dump($this->isGranted('IS_AUTHENTICATED_REMEMBERED'));
        var_dump($this->isGranted('IS_AUTHENTICATED_FULLY'));
        return $this->render(
            'users/user.html.twig',
            [
                'module' => $module,
                'service' => $service
            ]
        );
    }

}
