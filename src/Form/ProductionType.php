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
use App\Repository\TechnologyRepository;


/**
 * Class ProductionType
 * @package App\Form
 *
 * Classe contenant le constructeur du formulaire d'ajout de l'entité Production
 */
class ProductionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Constructeur du formulaire d'ajout de l'entité Production
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('name', TextType::class)
            ->add('url', TextType::class)
            ->add('github', TextType::class, array('required' => false))
            ->add('description', TextareaType::class, array('required' => false))
            ->add('date', DateType::class,
                array(
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                ) )
            ->add('thumbnail', FileType::class)
            ->add('image', FileType::class)
            ->add('preview', FileType::class)
            ->add('published', CheckboxType::class, array('required' =>false))
            ->add('productionCategory', EntityType::class, array(
                'class'         => 'App:ProductionCategory',
                'choice_label'  => 'name',
                'multiple'      => false
            ))
            ->add('technologies', EntityType::class, array(
                'class'         => 'App:Technology',
                'choice_label'  => 'name',
                'multiple'      => true,
                'query_builder' => function(TechnologyRepository $repository){
                    return $repository->getAllMasteredAndPublished();
                }
            ))
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Production::class,
        ]);
    }
}
