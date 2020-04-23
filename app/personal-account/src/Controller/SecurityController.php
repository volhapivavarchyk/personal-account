<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension as FormHttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;
use VP\PersonalAccount\Forms\UserType;
use VP\PersonalAccount\Entity\User;
use VP\PersonalAccount\Entity\Role;
use VP\PersonalAccount\Entity\Position;
use VP\PersonalAccount\Entity\Interest;

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

    /**
     * @Route("/registration", defaults={"_fragment" = "header-registration"}, name="registration", methods={"GET"})
     */
    public function registration(): Response
    {
        $user = new User();
        $role = new Role();
        $position = new Position();
        $interest = new Interest();
        $user->addRole($role);
        $user->getPositions()->add($position);
        $user->getInterests()->add($interest);

        $formFactory = Forms::CreateFormFactoryBuilder()
            ->addExtension(new FormHttpFoundationExtension())
            ->addExtension(new ValidatorExtension(Validation::createValidator()))
            ->getFormFactory();
        $builderForm = $formFactory->createBuilder(UserType::class, $user);
        $registrationform = $builderForm->getForm();

        return $this->render(
            'security/registration.html.twig',
            [
                'formregistration' => $registrationform->createView(),
                'error' => '',
            ]);
    }

}

