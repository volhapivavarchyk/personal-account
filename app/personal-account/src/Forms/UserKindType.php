<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\{Length, Regex};
use VP\PersonalAccount\Entity\UserKind;

class UserKindType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'user.kind',
                'translation_domain' => 'forms',
                'required' => true,
                'attr' => [
                    'placeholder' => 'вид пользователя',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserKind::class,
        ]);
    }
}