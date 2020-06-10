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
use VP\PersonalAccount\Entity\UserPosition;
use VP\PersonalAccount\Forms\UserType;
use VP\PersonalAccount\Entity\User;
use VP\PersonalAccount\Entity\UserKind;
use VP\PersonalAccount\Entity\Role;
use VP\PersonalAccount\Entity\UserStudentGroup;
use VP\PersonalAccount\Entity\Position;
use VP\PersonalAccount\Entity\Interest;
//use VP\PersonalAccount\Repository\UserKindRepository;
//use VP\PersonalAccount\Repository\RoleRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/registration", defaults={"_fragment" = "header-registration"}, name="registration", methods={"GET", "POST"})
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        // helper vars
        $captchaError = '';

//        $isAjax = $request->isXmlHttpRequest();
//        if ($isAjax) {
//            return new Response('This is ajax response '.$request->request->get('department'));
//        }

        // initialize a department id for choosing positions
        $idDepartment = $request->request->get('department') ? $request->request->get('department') : null;
        $idFaculty = $request->request->get('faculty') ? $request->request->get('faculty') : null;
        $idSpeciality = $request->request->get('speciality') ? $request->request->get('speciality') : null;

        $user = new User();
        $defaultUserKind = $this->getDoctrine()
            ->getRepository(UserKind::class)
            ->findByDefault();
        $user->setUserKind($defaultUserKind);
        $defaultRole = $this->getDoctrine()
            ->getRepository(Role::class)
            ->findByDefault();
        $user->setRole($defaultRole);

        $registrationForm = $this->createForm(
            UserType::class,
            $user,
            [
                'id_department' => $idDepartment,
                'id_faculty' => $idFaculty,
                'id_speciality' => $idSpeciality,
            ]);
        $registrationForm->handleRequest($request);
        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
            if (!empty($recaptchaResponse)) {
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $_ENV['PERSONAL_ACCOUNT_CAPTCHA_URL'],
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        'secret' => $_ENV['PERSONAL_ACCOUNT_CAPTCHA_SECRET'],
                        'response' => $recaptchaResponse,
                    )
                ]);
                $response = curl_exec($curl);
                curl_close($curl);
                if (strpos($response, '"success": true') !== false) {
                    // add user to DB and redirect to success route
                    //$user = $registrationForm->getData();
                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);

//                    $em = $this->getDoctrine()->getManager();
//                    $em->persist($user);
//                    $em->flush();
//                    if (strcmp($user->getUserKind()->getName(),$_ENV['EMPLOYEE']) > 0) {
//                        $userPosition = new UserPosition();
//                        $userPosition->setPosition();
//                        $userPosition->setUser($user);
//                        $userPosition->setDateStart();
//                        $em->persist($userPosition);
//                        $em->flush();
//                    } elseif (strcmp($user->getUserKind()->getName(),$_ENV['STUDENT']) > 0) {
//                        $userStudentGroup = new UserStudentGroup();
//                        $userStudentGroup->setStudentGroup();
//                        $userStudentGroup->setUser($user);
//                        $userStudentGroup->setDateStart();
//                        $em->persist($userStudentGroup);
//                        $em->flush();
//                    }
                    //return $this->redirectToRoute('user-success');
                } else {
                    $captchaError = 'Не верно введена captcha';
                }
            } else {
                $captchaError = 'Не введена captcha';
            }
        }

        return $this->render(
            'security/registration.html.twig',
            [
                'formregistration' => $registrationForm->createView(),
                'error' => '',
                'captchaError' => $captchaError,
                'captchaKey' => $_ENV['PERSONAL_ACCOUNT_CAPTCHA_KEY'],
            ]);
    }

}

