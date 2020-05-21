<?php
declare(strict_types=1);

namespace VP\PersonalAccount\Forms;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntityTreeType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $choices = [];

        foreach ($view->vars['choices'] as $choice) {
            if ($choice->data->getParent() === null) {
                $choices[$choice->value] = $choice->data;
            }
        }

        $choices = $this->buildTreeChoices($choices);
        $view->vars['choices'] = $choices;
    }

    public function buildTreeChoices($choices, $level = 0)
    {
        $result = [];

        foreach ($choices as $choice) {
            $result[$choice->getId()] = new ChoiceView($choice, (string) $choice->getId(), str_repeat('--', $level) . ' ' . $choice->getName(), []);
            if (!$choice->getChildren()->isEmpty()) {
                $result = array_merge($result, $this->buildTreeChoices($choice->getChildren(), $level+1));
            }
        }

        return $result;
    }

    public function getParent()
    {
        return EntityType::class;
    }
}