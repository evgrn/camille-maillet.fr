<?php

namespace App\Form;

use App\Entity\Production;


use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('url', TextType::class)
            ->add('description', TextareaType::class)
            ->add('date', DateType::class)
            ->add('thumbnail', FileType::class)
            ->add('image', FileType::class)
            ->add('published', CheckboxType::class, array('required' =>false))
            ->add('productionCategory', EntityType::class, array(
                'class'         => 'App:ProductionCategory',
                'choice_label'  => 'name',
                'multiple'      => false
            ))
            ->add('technologies', EntityType::class, array(
                'class'         => 'App:Technology',
                'choice_label'  => 'name',
                'multiple'      => true
            ))
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Production::class,
        ]);
    }
}
