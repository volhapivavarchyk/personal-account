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
use VP\PersonalAccount\Entity\Interest;
use VP\PersonalAccount\Entity\Position;
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
                }
            ])
            ->add('interests', EntityType::class, [
                'label' => 'user.interests',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Interest::class,
                'mapped' => false,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('userkind', EntityType::class, [
                'label' => 'user.kind',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => UserKind::class,
                'mapped' => false,
                'choice_label' =>  function (UserKind $userkind){
                    return $userkind->getName();
                },
                'required' => true,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('roles', EntityType::class, [
                'label' => 'user.roles',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Role::class,
                'mapped' => false,
                'choice_label' =>  function (Role $role){
                    return $role->getName();
                },
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    $qb = $er->createQueryBuilder('r');
                    $qb->where(
                        $qb->expr()->isNull('r.parent')
                    );
                    return $qb;
                },
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
