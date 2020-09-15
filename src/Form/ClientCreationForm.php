<?php

namespace App\Form;

use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientCreationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Имя'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Фамилия'
            ])
            ->add('patronymic', TextType::class, [
                'label' => 'Отчество'
            ])
            ->add('phone', TextType::class, [
                'label' => 'Телефон'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('data-class', Client::class);
    }
}