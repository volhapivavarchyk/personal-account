<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\{RepeatedType,
    TextType,
    PasswordType,
    EmailType,
    CollectionType,
    SubmitType,
    CheckboxType};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, Regex};
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use VP\PersonalAccount\Entity\Department;
use VP\PersonalAccount\Entity\Faculty;
use VP\PersonalAccount\Entity\StudentGroup;
use VP\PersonalAccount\Entity\Speciality;
use VP\PersonalAccount\Entity\User;
use VP\PersonalAccount\Entity\Role;
use VP\PersonalAccount\Entity\Interest;
use VP\PersonalAccount\Entity\Position;
use VP\PersonalAccount\Entity\UserKind;
use VP\PersonalAccount\Repository\DepartmentRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use VP\PersonalAccount\Forms\EventListener\AddSpecialityFieldSubscriber;

class UserType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'user.username',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'IvanovI',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => "Имя пользователя должно состоять из <b> букв латинского алфавита</b>, <b>цифр</b> и <b>специальных симвлов</b>. Должно содержать <b>не менее 3 символов</b>",
                    'data-html' => 'true',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Имя пользователя должно содержать не менее 3 символов',
                        'max' => 128,
                        'maxMessage' => 'Имя пользователя должно содержать не более 128 символов',
                    ]),
                    new Regex([
                        'pattern' => '/[A-Za-z0-9\s]*/',
                        'match' => true,
                        'message' => 'Имя пользователя должно содержать буквы латинского алфавита, цифры и специальные символы'
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
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Пример: oit@barsu.by',
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'user.password',
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
                    'label' => 'user.password_repeat',
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
            ])
            ->add('firstname', TextType::class, [
                'label' => 'user.firstname',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Иван',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Имя на русском языке должно состоять из <b>букв русского алфавита</b>',
                    'data-html' => 'true',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Имя должно содержать не менее 3 символов',
                        'max' => 128,
                        'maxMessage' => 'Имя должно содержать не более 128 символов',
                    ]),
                    new Regex([
                        'pattern' => '/[А-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Имя на русском языке должно состоять из букв русского алфавита'
                    ]),
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'user.lastname',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Иванов',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Фамилия на русском языке должна состоять из <b>букв русского алфавита</b>',
                    'data-html' => 'true',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Фамилия должна содержать не менее 3 символов',
                        'max' => 120,
                        'maxMessage' => 'Фамилия должна содержать не более 128 символов',
                    ]),
                    new Regex([
                        'pattern' => '/[А-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Фамилия на русском языке должна состоять из букв русского алфавита'
                    ]),
                ],
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'user.patronymic',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Иванович',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Отчество на русском языке должно состоять из <b>букв русского алфавита</b>',
                    'data-html' => 'true',
                ],
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Отчество должно содержать не менее 3 символов',
                        'max' => 120,
                        'maxMessage' => 'Отчество должно содержать не более 128 символов',
                    ]),
                    new Regex([
                        'pattern' => '/[А-Яа-я0-9\s]*/',
                        'match' => true,
                        'message' => 'Отчество на русском языке должно состоять из букв русского алфавита'
                    ]),
                ],
            ]);
        $builder
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
                'query_builder' => function(EntityRepository $er) {
                    $qb = $er->createQueryBuilder('uk')
                        ->orderBy('uk.id', 'ASC');
                    return $qb;
                },
                'data' => $options['data']->getUserKind(),
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'custom-control custom-radio',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Тип пользователя ...',
                ],
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
                'attr' => [
                    'class' => 'custom-control custom-radio',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Роль пользователя ...',
                ],
                'label_attr' => ['class' => 'custom-control-label'],
            ]);
        $builder
            ->add('department', EntityTreeType::class, [
                'label' => 'user.department',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Department::class,
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => '-- выберите подразделение --',
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Подразделение университета, в котором работает сотрудник',
                ],
            ])
            -> addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $form->add('positions', EntityType::class, [
                    'label' => 'user.positions',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'class' => Position::class,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('p');
                        $idDepartment = $options['id_department'];
                        $qb->where('p.department = ?1')->setParameter(1, $idDepartment);
                        return $qb;
                    },
                    'placeholder' => '-- выберите должность --',
                    'attr' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Должность сотрудника университета',
                    ],
                ]);
            });
        $builder->get('department')->addEventListener(
            FormEvents::POST_SUBMIT,
            function(FormEvent $event) use ($options) {
                $form = $event->getForm()->getParent();
                $department= $event->getForm()->getData();
                $positions = $department === null ? [] : $department->getPositions();
                $form->add('positions', EntityType::class, [
                    'label' => 'user.positions',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'class' => Position::class,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'choices' => $positions,
                    'placeholder' => '-- выберите должность --',
                    'attr' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Должность сотрудника университета',
                    ],
                ]);
            });
        $builder
            ->add('faculty', EntityType::class, [
                'label' => 'user.faculty',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Faculty::class,
                'mapped' => false,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'placeholder' => '-- выберите факультет --',
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Факультет, на котором учится студент',
                ],
            ]);
        $builder
//            ->addEventSubscriber(new AddSpecialityFieldSubscriber());
            -> addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $form->add('speciality', EntityType::class, [
                    'label' => 'user.speciality',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'class' => Speciality::class,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('f');
                        $idFaculty = $options['id_faculty'];
                        $qb->where('f.faculty = ?1')->setParameter(1, $idFaculty);
                        return $qb;
                    },
                    'placeholder' => '-- выберите специальность --',
                    'attr' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Специальность, на которой учится студент',
                    ],
                ]);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $form->add('speciality', EntityType::class, [
                    'label' => 'user.speciality',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'class' => Speciality::class,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('f');
                        $idFaculty = $options['parameters']['faculty'];
                        $qb->where('f.faculty = ?1')->setParameter(1, $idFaculty);
                        return $qb;
                    },
                    'placeholder' => '-- выберите специальность --',
                    'attr' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Специальность, на которой учится студент',
                    ],
                ]);
            });
        $builder
            -> addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $form->add('group', EntityType::class, [
                    'label' => 'user.group',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'class' => StudentGroup::class,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('g');
                        $idSpeciality = $options['id_speciality'];
                        $qb->where('g.speciality = ?1')->setParameter(1, $idSpeciality);
                        return $qb;
                    },
                    'placeholder' => '-- выберите группу --',
                    'attr' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Группа, в которой учится студент',
                    ],
                ]);
            })
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                $form->add('group', EntityType::class, [
                    'label' => 'user.group',
                    'label_translation_parameters' => [],
                    'translation_domain' => 'forms',
                    'class' => StudentGroup::class,
                    'mapped' => false,
                    'choice_label' => 'name',
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'query_builder' => function(EntityRepository $er) use ($options) {
                        $qb = $er->createQueryBuilder('g');
                        $idSpeciality = $options['parameters']['speciality'];
                        $qb->where('g.speciality = ?1')->setParameter(1, $idSpeciality);
                        return $qb;
                    },
                    'placeholder' => '-- выберите группу --',
                    'attr' => [
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'left',
                        'data-title' => 'Группа, в которой учится студент',
                    ],
                ]);
            });
        $builder
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
                'attr' => [
                    'class' => 'custom-control custom-radio',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Перечень интересов пользователя. Можно выбрать множество',
                ],
                'label_attr' => ['class' => 'custom-control-label'],
            ]);
        $builder
            ->add('termsAccepted', CheckboxType::class, array(
                'label' => 'user.terms-accepted',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
                'constraints' => new IsTrue(),
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'id_department' => null,
            'id_faculty' => null,
            'id_speciality' => null,
            'parameters' => [],
        ]);
    }
}

