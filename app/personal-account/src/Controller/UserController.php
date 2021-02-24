<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use VP\PersonalAccount\Forms\PositionType;
use VP\PersonalAccount\Forms\ProfileType;
use VP\PersonalAccount\Helper\ProfileHelper;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;

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
                $data = (new ProfileHelper($this->getUser()))->createForm();
                break;
            case 2:
                $moduleName = 'moodle';
                $data = [];
                break;
            case 3:
                $moduleName = 'elib';
                $data = [];
                break;
            case 4:
                $moduleName = 'calendar';
                $data = [];
                break;
            case 5:
                $moduleName = 'videoconference';
                $data = [];
                break;
            case 6:
                $moduleName = 'email';
                $data = [];
                break;
            case 7:
                $moduleName = 'documents';
                $data = [];
                break;
            case 8:
                $moduleName = 'university';
                $data = [];
                break;
            case 9:
                $moduleName = 'studentCadr';
                $data = [];
                break;
            case 10:
                $moduleName = 'schedule';
                $data = [];
                break;
            case 11:
                $moduleName = 'originality';
                $data = [];
                break;
            case 12:
                $moduleName = 'departments';
                $data = [];
                break;
            case 13:
                $moduleName = 'science';
                $data = [];
                break;
            case 14:
                $moduleName = 'technologyCentre';
                $data = [];
                break;
            case 15:
                $moduleName = 'additional';
                $data = [];
                break;
            case 16:
                $moduleName = 'campus';
                $data = [];
                break;
            case 17:
                $moduleName = 'poster';
                $data = [];
                break;
            case 18:
                $moduleName = 'psychologist';
                $data = [];
                break;
            case 19:
                $moduleName = 'newspaper';
                $data = [];
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
