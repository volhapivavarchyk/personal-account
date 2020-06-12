<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use VP\PersonalAccount\Entity\Faculty;
use VP\PersonalAccount\Entity\Speciality;

class AddSpecialityFieldSubscriber implements EventSubscriberInterface
{
    protected $options;

    public function __construct()
    {
        $this->options = '';
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function addSpecialityForm(FormFactoryInterface $form, $idFaculty)
    {
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
    }

    public function preSetData(FormEvent $event)
    {
        var_dump($event->getForm());
        $this->addSpecialityForm($event->getForm(), $this->options['id_faculty']);
    }
}