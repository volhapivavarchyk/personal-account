<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use VP\PersonalAccount\Forms\EventListener\{
    AddPositionFieldSubscriber,
    AddSpecialityFieldSubscriber,
    AddGroupFieldSubscriber
};
use VP\PersonalAccount\Entity\{
    Department,
    Faculty,
    Interest,
    Position,
    Role,
    User,
    UserKind
};
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{
    AbstractType,
    FormBuilderInterface,
    FormEvent,
    FormEvents
};
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType,
    ChoiceType,
    CollectionType,
    DateType,
    EmailType,
    PasswordType,
    RepeatedType,
    SubmitType,
    TextType};
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\{
    Length,
    Regex,
    IsTrue
};

class ProfileType extends AbstractType
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
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => "Имя пользователя должно состоять из <b> букв латинского алфавита</b>, <b>цифр</b> и <b>специальных симвлов</b>. Должно содержать <b>не менее 3 символов</b>",
                    'data-html' => 'true',
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
            ]);
        $builder
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
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'user.patronymic',
                'translation_domain' => 'forms',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Иванович',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Отчество на русском языке должно состоять из <b>букв русского алфавита</b>',
                    'data-html' => 'true',
                ],
            ]);
        $builder
            ->add('enFirstname', TextType::class, [
                'label' => 'user.en-firstname',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ivan',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Имя на английском языке должно состоять из <b>букв английского алфавита</b>',
                    'data-html' => 'true',
                ],
            ])
            ->add('enLastname', TextType::class, [
                'label' => 'user.en-lastname',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ivanov',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Фамилия на английском языке должна состоять из <b>букв английского алфавита</b>',
                    'data-html' => 'true',
                ],
            ])
            ->add('enPatronymic', TextType::class, [
                'label' => 'user.en-patronymic',
                'translation_domain' => 'forms',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Ivanovich',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Отчество на английском языке должно состоять из <b>букв английского алфавита</b>',
                    'data-html' => 'true',
                ],
            ]);
        $builder
            ->add('userkind', ChoiceType::class, [
                'choices' => [
                    'Студент университета' => 2,
                    'Сотрудник университета' => 3,
                    'Гость' => 4,
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Тип пользователя ...',
                ],
                'disabled' => true,
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Пользователь' => 1,
                    'Гость' => 2,
                    'Администратор' => 3,
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Роль пользователя ...',
                ],
                'disabled' => true,
            ]);
        $builder
            ->add('userkind', ChoiceType::class, [
                'choices' => [
                    'Студент университета' => 2,
                    'Сотрудник университета' => 3,
                    'Гость' => 4,
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Тип пользователя ...',
                ],
                'disabled' => true,
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Пользователь' => 1,
                    'Гость' => 2,
                    'Администратор' => 3,
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Роль пользователя ...',
                ],
                'disabled' => true,
            ]);
        $builder
            ->add('faculty', ChoiceType::class, [
                'choices' => [
                    'Инженерный факультет' => 1,
                    'Факультет славянских и германских языков' => 2,
                    'Факультет педагогики и психологии' => 3,
                    'Факультет экономики и права' => 4,
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Факультет',
                ],
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('speciality', ChoiceType::class, [
                'choices' => [
                    'ТОСП' => 1,
                    'ИСТ' => 2,
                    'ТМ' => 3
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Специальность',
                ],
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('group', ChoiceType::class, [
                'choices' => [
                    'ТОСП 11' => 1,
                    'ТОСП 12' => 2,
                    'ИСТ 11' => 3
                ],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'custom-control-input'];
                },
                'attr' => [
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Группа',
                ],
                'disabled' => true,
                'mapped' => false,
            ])
            ->add('dateStartSpeciality', DateType::class, [
                'label' => 'user.date-start-speciality',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'd-m-yy',
                'html5' => false,
                'placeholder' => 'Выберите значение',
                'attr' => [
                    'class' => 'js-datepicker',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Дата начала обучения по специальности',
                ],
                'mapped' => false,
                'disabled' => true,
                'data' => new \DateTime('2020-09-01'),
            ]);
        $builder
            ->add('interest1', CheckboxType::class, array(
                'label' => 'Программирование',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ))
            ->add('interest2', CheckboxType::class, array(
                'label' => 'Искусственный интеллект',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
            ))
            ->add('interest3', CheckboxType::class, array(
                'label' => 'Иностранные языки',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ))
            ->add('interest4', CheckboxType::class, array(
                'label' => 'Экономика',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
            ))
            ->add('interest5', CheckboxType::class, array(
                'label' => 'Литература',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
            ));
        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Применить',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
