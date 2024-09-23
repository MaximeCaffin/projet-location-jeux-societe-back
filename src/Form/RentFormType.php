<?php

namespace App\Form;

use App\Entity\ContentRent;
use App\Entity\Rent;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => function ($user) {
                    return $user->getFirstname() . ' ' . $user->getLastname();
                },
            ])
            ->add('date_debut', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('date_fin', DateTimeType::class, [
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'Pending',
                    'Approved' => 'Approved',
                    'Returned' => 'Returned',
                ],
            ])
            ->add('contentRents', EntityType::class, [
                'class' => ContentRent::class,
                'choice_label' => function ($contentRent) {
                    return $contentRent->getGame()->getName();
                },
                'multiple' => true,
                'expanded' => false, 
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
            'csrf_protection' => true,
            'allow_extra_fields' => true,
        ]);
    }
}