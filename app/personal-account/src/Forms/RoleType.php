<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, Regex};
use VP\PersonalAccount\Entity\Role;

class RoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'role.name',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'администратор',
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
            //])
            //->add('children', CollectionType::class, [
            //    'label' => 'role.children',
            //    'label_translation_parameters' => [],
            //    'translation_domain' => 'forms',
            //    'entry_type' => RoleType::class,
            //    'entry_options' => ['label' => 'роль'],
            //    'by_reference' => false,
            //    'prototype' => true,
            //    'allow_add' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}