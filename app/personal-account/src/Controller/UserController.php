<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use VP\PersonalAccount\Helper\ProfileHelper;

class UserController extends AbstractController
{
    /**
     * @Route("/personal/{module}/{service}", methods="GET|POST", name="homepage")
     */
    public function action(Request $request, int $module = 0, int $service = 0): Response
    {
        switch ($module){
            case 0:
                $moduleName = 'content';
                $data = '';
                break;
            case 1:
                $moduleName = 'profile';
//                var_dump($this->getUser());
                $data = (new ProfileHelper($this->getUser()))->createForm();
                break;
            default:
                $moduleName = 'content';
                $data = '';
        }

        return $this->render(
            'users/user.html.twig',
            [
                'module' => $moduleName,
                'service' => $service,
                'data' => $data,
            ]
        );
    }

    /**
     * @Route("/edit-profile", methods="GET|POST", name="edit_profile")
     */
    public function editProfile(Request $request): Response
    {
        return $this->render(
            'users/user-profile.html.twig',
            [
                'module' => 'profile',
                'service' => 1
            ]
        );
    }

    /**
     * @Route("/temp", methods="GET|POST", name="temp")
     */
    public function action_temp(Request $request): Response
    {
        return $this->render('temp.html.twig', []);
    }
}
