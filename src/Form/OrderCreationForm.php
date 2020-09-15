<?php

namespace App\Form;

use App\Entity\Complexity;
use App\Entity\Order;
use App\Entity\Service;
use App\Entity\Urgency;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderCreationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('serviceName', EntityType::class, [
                'label' => 'Тип услуги',
                'class' => Service::class,
                'choice_label' => 'name'
            ])
            ->add('complexity', EntityType::class, [
                'label' => 'Сложность',
                'class' => Complexity::class,
                'choice_label' => 'name'
            ])
            ->add('urgency', EntityType::class, [
                'label' => 'Срочность',
                'class' => Urgency::class,
                'choice_label' => 'name'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Выбрать'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data-class' => Order::class
        ]);
    }
}