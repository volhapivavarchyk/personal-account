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
    UserStudentGroup
};
use VP\PersonalAccount\Forms\UserType;
use VP\PersonalAccount\Forms\ResetPasswordType;
use VP\PersonalAccount\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error !== null) {
            $this->addFlash('user-error', $error->getMessage());
        }

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'security/authorization.html.twig',
            [
                'last_username' => $lastUsername,
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
     * @throws \Exception
     */
    public function register(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerService $mailerService, \Swift_Mailer $mailer): Response
    {
        // helper vars
        $captchaError = '';
        $idDepartment = $request->request->get('department') ? $request->request->get('department') : null;
        $idFaculty = $request->request->get('faculty') ? $request->request->get('faculty') : null;
        $idSpeciality = $request->request->get('speciality') ? $request->request->get('speciality') : null;
        $parameters = $request->request->get('user') ? $request->request->get('user') : [];

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
                        'confirm_registration.html.twig'
                    );
                    $this->addFlash(
                        'user-success',
                        'Вы успешно прошли регистрацию в системе. 
                        Для активации Вашей учетной записи необходимо пройти по ссылке, отправленной на электронную почту. 
                        После активации учетной записи Вы сможете войти в систему.'
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
            $user->setConfirmationToken('');
            $user->setStatus($_ENV['STATUS_ACTIVE']);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('login');
        } else {
            return $this->render('security/token-expire.html.twig');
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
            return $this->redirectToRoute('register');
        }
        $user->setConfirmationToken($this->generateToken());
        $em->persist($user);
        $em->flush();
        $token = $user->getConfirmationToken();
        $email = $user->getEmail();
        $username = $user->getUsername();
        $mailerService->sendToken($mailer, $token, $email, $username, 'registration.html.twig');
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/forgotten-password", name="forgotten_password")
     * @param Request $request
     * @param MailerService $mailerService
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \Exception
     */
    public function forgottenPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder, MailerService $mailerService, \Swift_Mailer $mailer): Response
    {
        if($request->isMethod('POST')) {
            $username = $request->get('username');
            $user = null;
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);
            if($user === null) {
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $username]);
            }
            if($user === null) {
                $this->addFlash('user-error', 'Пользователя не существует');
                return $this->redirectToRoute('forgotten_password');
            }
            $token = $this->generateToken();
            $user->setPlainPassword($token);
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setPlainPassword('');
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $email = $user->getEmail();
            $username = $user->getUsername();
            $mailerService->sendToken($token, $email, $username, 'forgotten_password.html.twig');
            $this->addFlash('user-success', 'На адрес электронной почты выслан новый пароль. В случае необходимости пароль можно сменить в настройках личного кабинета');
            return $this->redirectToRoute('login');
        }
        return $this->render('security/forgotten-password.html.twig');
    }
    /**
     * @Route("/reset-password/{token}", defaults={"token" = ""}, name="reset_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            if($user) {
                if($passwordEncoder->isPasswordValid($user, $form->get('password')->getData())) {
                    $user->setPlainPassword('');
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                    $em->persist($user);
                    $em->flush();
                    $this->addFlash('user-success', 'Пароль успешно изменен');
                    return $this->redirectToRoute('homepage');
                }
            } else {
                $this->addFlash('not-authorise-user', 'Вы не вошли в систему. Возможность доступна только для авторизованных пользователей.');
                return $this->redirectToRoute('reset_password');
            }
        }
        return $this->render('security/reset-password.html.twig', [
            'form' => $form->createView()
        ]);
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

