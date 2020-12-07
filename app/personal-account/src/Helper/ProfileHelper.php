<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Helper;

use VP\PersonalAccount\Forms\ProfileType;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Forms;

class ProfileHelper
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     */
    public function createForm()
    {
        $formFactory = Forms::createFormFactoryBuilder()
            ->addExtension(new HttpFoundationExtension())
            ->getFormFactory();
        return $formFactory->createBuilder(ProfileType::class, $this->user)->getForm()->createView();
    }
}