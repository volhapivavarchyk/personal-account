<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, PasswordType, EmailType, CollectionType, SubmitType};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, Regex};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VP\PersonalAccount\Entity\User;
use VP\PersonalAccount\Entity\Role;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'user.username',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'IvanovI',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Это значение слишком короткое. Оно должно иметь 3 или более символов',
                        'max' => 120,
                        'maxMessage' => 'Это значение слишком длинное. Оно должно иметь 120 или менее символов',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Za-zА-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Имя пользователя должно содержать буквы и цифры'
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'user.password',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => '******',
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Иван',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Это значение слишком короткое. Оно должно иметь 3 или более символов',
                        'max' => 120,
                        'maxMessage' => 'Это значение слишком длинное. Оно должно иметь 120 или менее символов',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Za-zА-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Фамилия должно содержать буквы'
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Иванов',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Это значение слишком короткое. Оно должно иметь 3 или более символов',
                        'max' => 120,
                        'maxMessage' => 'Это значение слишком длинное. Оно должно иметь 120 или менее символов',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Za-zА-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Имя пользователя должно содержать буквы и цифры'
                    ]),
                ],
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'user.patronymic',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Иванович',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Это значение слишком короткое. Оно должно иметь 3 или более символов',
                        'max' => 120,
                        'maxMessage' => 'Это значение слишком длинное. Оно должно иметь 120 или менее символов',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Za-zА-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Имя пользователя должно содержать буквы и цифры'
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'mailbox@hostname',
                ],
            ])
            ->add('roles', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'class' => Role::class,
                'choice_label' => 'name',
            ])
//            ->add('roles', CollectionType::class, [
//                'label' => 'user.roles',
//                'label_translation_parameters' => [],
//                'translation_domain' => 'forms',
//                'entry_type' => RoleType::class,
//                'entry_options' => ['label' => 'роль'],
//                'by_reference' => false,
//                'prototype' => true,
//                'allow_add' => true,
//            ])
            ->add('positions', CollectionType::class, [
                'label' => 'user.positions',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'entry_type' => PositionType::class,
                'entry_options' => ['label' => 'должность'],
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
            ])
            ->add('interests', CollectionType::class, [
                'label' => 'user.interests',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'entry_type' => InterestType::class,
                'entry_options' => ['label' => 'иснтерес'],
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
            ])
            ->add('histories', CollectionType::class, [
                'label' => 'user.histories',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'entry_type' => HistoryType::class,
                'entry_options' => ['label' => 'история взаимодействия'],
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
            ])
            ->add('messages', CollectionType::class, [
                'label' => 'user.messages',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'entry_type' => MessageType::class,
                'entry_options' => ['label' => 'сообщения'],
                'by_reference' => false,
                'prototype' => true,
                'allow_add' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
