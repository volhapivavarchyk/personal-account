<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use VP\PersonalAccount\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, Regex};
use Symfony\Component\Form\Extension\Core\Type\{
    PasswordType,
    RepeatedType
};

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'user.password-old',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'always_empty' => true,
                'required' => true,
                'help' => 'Введите Ваш действующий пароль',
                'help_attr'=> ['class'=> 'text-primary'],
                'mapped' => true,

            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'user.password-new',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'required' => true,
                    'attr' => [
                        'placeholder' => '******',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Пароль должен состоять из <b>букв латинского алфавита</b>, <b>цифр</b> и <b>специальных симвлов</b>.
                        Должен содержать <b>не менее 6 символов</b>.
                        Обязательно наличие <b>цифры</b>, <b>строчной и прописной буквы</b>, <b>специального символа</b>',
                        'data-html' => 'true',
                    ],
                    'constraints' => [
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Пароль должен содержать не менее 6 символов',
                            'max' => 128,
                            'maxMessage' => 'Пароль должен содержать не более 128 символов',
                        ]),
                        new Regex([
                            'pattern' => '/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/',
                            'match' => true,
                            'message' => 'Пароль должен состоять из букв латинского алфавита, цифр и специальных симвлов. 
                                Обязательно наличие цифры, строчной и прописной буквы, специального символа'
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'user.password-new-repeat',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'required' => true,
                    'attr' => [
                        'placeholder' => '******',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'В поле необходимо ввести строку полностью совпадающую с введенным паролем',
                    ],
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}