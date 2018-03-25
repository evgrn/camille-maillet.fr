<?php

namespace App\Form;

use App\Entity\Career;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CareerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, array(
                'choices'  => array(
                    'Formation' => 'Formation',
                    'Entreprise' => 'Entreprise',
                ),
            ))
            ->add('name', TextType::class )
            ->add('structure', TextType::class, array('required' => false))
            ->add('description', TextareaType::class, array('required' => false) )
            ->add('date', DateType::class,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                ) )
            ->add('published', CheckboxType::class, array('required' => false) )
            ->add('submit', SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Career::class
        ]);
    }
}
