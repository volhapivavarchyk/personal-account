<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms\EventListener;

use VP\PersonalAccount\Entity\StudentGroup;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{FormEvent, FormEvents, FormInterface};
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddGroupFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function addSpecialityForm(FormInterface $form, $idSpeciality)
    {
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
            'query_builder' => function(EntityRepository $er) use ($idSpeciality) {
                $qb = $er->createQueryBuilder('g');
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
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $idSpeciality = $event->getForm()->getConfig()->getOptions()['id_speciality'];
        $this->addSpecialityForm($form, $idSpeciality);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $idSpeciality = $event->getForm()->getConfig()->getOptions()['parameters']['speciality'];
        $this->addSpecialityForm($form, $idSpeciality);
    }
}