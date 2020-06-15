<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms\EventListener;

use VP\PersonalAccount\Entity\Position;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{FormEvent, FormEvents, FormInterface};
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddPositionFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function addSpecialityForm(FormInterface $form, $idDepartment)
    {
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
            'query_builder' => function(EntityRepository $er) use ($idDepartment) {
                $qb = $er->createQueryBuilder('p');
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
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $idDepartment = $event->getForm()->getConfig()->getOptions()['id_department'];
        $this->addSpecialityForm($form, $idDepartment);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $idDepartment = $event->getForm()->getConfig()->getOptions()['parameters']['department'];
        $this->addSpecialityForm($form, $idDepartment);
    }
}