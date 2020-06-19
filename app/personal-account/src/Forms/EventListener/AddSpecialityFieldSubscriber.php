<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms\EventListener;

use VP\PersonalAccount\Entity\Speciality;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\{FormEvent, FormEvents, FormInterface};
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddSpecialityFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function addSpecialityForm(FormInterface $form, $idFaculty)
    {
        $form
            ->add('speciality', EntityType::class, [
                'label' => 'user.speciality',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'class' => Speciality::class,
                'mapped' => false,
                'choice_label' => 'name',
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function(EntityRepository $er) use ($idFaculty) {
                    $qb = $er->createQueryBuilder('f');
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
        $form
            ->add('dateStartSpeciality', DateType::class, [
                'label' => 'user.date-start-speciality',
                'label_translation_parameters' => [],
                'translation_domain' => 'forms',
                'mapped' => false,
                'required' => false,
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'dd-mm-yyyy',
                'html5' => false,
                'placeholder' => 'Выберите значение',
                'attr' => [
                    'class' => 'js-datepicker',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'left',
                    'data-title' => 'Дата начала обучения по специальности',
                ],
            ]);
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $idFaculty = $event->getForm()->getConfig()->getOptions()['id_faculty'];
        $this->addSpecialityForm($form, $idFaculty);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $idFaculty = $event->getForm()->getConfig()->getOptions()['parameters']['faculty'];
        $this->addSpecialityForm($form, $idFaculty);
    }
}