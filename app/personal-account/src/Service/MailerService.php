<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Service;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailerService extends AbstractController
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct()
    {
        $this->mailer = new \Swift_Mailer();
    }

    /**
     * @param $token
     * @param $username
     * @param $template
     * @param $to
     */
    public function sendToken($token, $to, $username, $template)
    {
        $message = (new \Swift_Message('Подтверждение по электронной почте'))
            ->setFrom('pivovarchyk@tut.by')
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'emails/' . $template,
                    [
                        'token' => $token,
                        'username' => $username
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
    }
}