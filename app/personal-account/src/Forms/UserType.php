<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, PasswordType, EmailType, CollectionType, SubmitType};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, Regex};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VP\PersonalAccount\Entity\Department;
use VP\PersonalAccount\Entity\User;
use VP\PersonalAccount\Entity\Role;
use VP\PersonalAccount\Entity\Interest;
use VP\PersonalAccount\Entity\Position;
use VP\PersonalAccount\Entity\UserKind;
use Doctrine\ORM\EntityRepository;

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
                        'pattern' => '/[A-Za-z0-9\s]*/',
                        'match' => true,
                        'message' => 'Имя пользователя должно содержать буквы латинского алфавита, цифры и символы'
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
            ->add('department', EntityType::class, [
                'label' => 'user.department',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Department::class,
                'mapped' => false,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'required'   => false,
            ])
            ->add('positions', EntityType::class, [
                'label' => 'user.positions',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Position::class,
                'mapped' => false,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'group_by' => function (Position $position, $key, $value) {
                    return $position->getDepartment();
                },
                'required'   => false,
            ])
            ->add('interests', EntityType::class, [
                'label' => 'user.interests',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Interest::class,
                'mapped' => false,
                'choice_label' => 'name',
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'required' => false,
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'custom-control custom-radio'],
                'label_attr' => ['class' => 'custom-control-label'],
            ])
            ->add('userkind', EntityType::class, [
                'label' => 'user.kind',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => UserKind::class,
                'mapped' => false,
                'choice_label' =>  function (UserKind $userkind, $key, $name){
                    return $userkind->getName();
                },
                'choice_value' => function(UserKind $userkind = null) {
                    return $userkind == null ? '' : $userkind->getId();
                },
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'data' => $options['data']->getUserKind(),
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'attr' => ['class' => 'custom-control custom-radio'],
                'label_attr' => ['class' => 'custom-control-label'],
            ])
            ->add('roles', EntityType::class, [
                'label' => 'user.roles',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Role::class,
                'mapped' => false,
                'choice_label' =>  function (Role $role){
                    return $role->getNameRu();
                },
                'choice_value' => function(Role $role = null) {
                    return $role == null ? '' : $role->getName();
                },
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'data' => $options['data']->getRole(),
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('r');
                    $qb->where(
                        $qb->expr()->isNull('r.parent')
                    );
                    return $qb;
                },
                'attr' => ['class' => 'custom-control custom-radio'],
                'label_attr' => ['class' => 'custom-control-label'],
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

