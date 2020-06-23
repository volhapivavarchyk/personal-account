<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Controller;

use \DateTime;
use VP\PersonalAccount\Entity\{
    Position,
    User,
    UserKind,
    Role,
    UserPosition,
    StudentGroup,
    UserStudentGroup,
};
use VP\PersonalAccount\Forms\UserType;
use VP\PersonalAccount\Repository\RoleRepository;
use VP\PersonalAccount\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
//use Symfony\Component\Form\Forms;
//use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension as FormHttpFoundationExtension;
//use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
//use Symfony\Component\Validator\Validation;

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
     * @Route("/register", defaults={"_fragment" = "header-register"}, name="register", methods={"GET", "POST"})
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerService $mailerService
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \Exception     */
    public function register(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerService $mailerService, \Swift_Mailer $mailer): Response
    {
        // helper vars
        $captchaError = '';

        $idDepartment = $request->request->get('department') ? $request->request->get('department') : null;
        $idFaculty = $request->request->get('faculty') ? $request->request->get('faculty') : null;
        $idSpeciality = $request->request->get('speciality') ? $request->request->get('speciality') : null;
        $parameters = $request->request->get('user') ? $request->request->get('user') : [];

        //var_dump($request->request->get('user'));

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
                'parameters' => $parameters,
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
                    $em = $this->getDoctrine()->getManager();
                    // add user to DB and redirect to success route
                    $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                    $user->setPassword($password);
                    $userKind = $em->find(UserKind::class, $request->request->get('user')['userkind']);
                    $user->setUserKind($userKind);
//                    $role = $em->getRepository(Role::class)->findOneBy(['name' => $request->request->get('user')['roles']]);
                    $user->setRole($defaultRole);
                    $user->setStatus($_ENV['STATUS_INACTIVE']);
                    $confirmationToken = $this->generateToken();
                    $user->setConfirmationToken($confirmationToken);
                    $em->persist($user);
                    $em->flush();
                    if (strcmp($user->getUserKind()->getName(),$_ENV['USER_EMPLOYEE']) === 0) {
                        $position = $em->find(Position::class, $request->request->get('user')['positions']);
                        $userPosition = new UserPosition();
                        $userPosition->setPosition($position);
                        $userPosition->setUser($user);
                        $dateStart = new \DateTime($request->request->get('user')['dateStartPosition']);
                        $userPosition->setDateStart($dateStart);
                        $em->persist($userPosition);
                        $em->flush();
                    } elseif (strcmp($user->getUserKind()->getName(),$_ENV['USE_STUDENT']) === 0) {
                        $studentGroup = $em->find(StudentGroup::class, $request->request->get('user')['group']);
                        $userStudentGroup = new UserStudentGroup();
                        $userStudentGroup->setStudentGroup($studentGroup);
                        $userStudentGroup->setUser($user);
                        $dateStart = new \DateTime($request->request->get('user')['dateStartSpeciality']);
                        $userStudentGroup->setDateStart($dateStart);
                        $em->persist($userStudentGroup);
                        $em->flush();
                    }

                    $token = $user->getConfirmationToken();
                    $email = $user->getEmail();
                    $username = $user->getUsername();
                    $mailerService->sendToken(
                        $token,
                        $email,
                        $username,
                        'registration.html.twig'
                    );
                    $this->addFlash(
                        'user-error',
                        'Ваша регистрация была подтверждена, вы получите подтверждение по электронной почте для активации вашей учетной записи и сможете войти в систему'
                    );
                    //return $this->redirectToRoute('user-success');
                    return $this->redirectToRoute('login');
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
                'formRegister' => $registrationForm->createView(),
                'error' => '',
                'captchaError' => $captchaError,
                'captchaKey' => $_ENV['PERSONAL_ACCOUNT_CAPTCHA_KEY'],
            ]);
    }

    /**
     * @Route("/account/confirm/{token}/{username}", name="confirm_account")
     * @param $token
     * @param $username
     * @return Response
     */
    public function confirmAccount($token, $username): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['username' => $username]);
        $tokenExist = $user->getConfirmationToken();
        if($token === $tokenExist) {
            $user->setConfirmationToken(null);
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_login');
        } else {
            return $this->render('registration/token-expire.html.twig');
        }
    }
    /**
     * @Route("/send-token-confirmation", name="send_confirmation_token")
     * @param Request $request
     * @param MailerService $mailerService
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function sendConfirmationToken(Request $request, MailerService $mailerService, \Swift_Mailer $mailer): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $email = $request->request->get('email');
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
        if($user === null) {
            $this->addFlash('not-user-exist', 'Пользователь не найден');
            return $this->redirectToRoute('app_register');
        }
        $user->setConfirmationToken($this->generateToken());
        $em->persist($user);
        $em->flush();
        $token = $user->getConfirmationToken();
        $email = $user->getEmail();
        $username = $user->getUsername();
        $mailerService->sendToken($mailer, $token, $email, $username, 'registration.html.twig');
        return $this->redirectToRoute('app_login');
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}

