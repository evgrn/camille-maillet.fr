<?php
namespace App\Form;

use App\Entity\Technology;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


/**
 * Class TechnologyType
 * @package App\Form
 *
 * Classe contenant le constructeur de formilaire pour l'entité Technology
 */
class TechnologyType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Constructeur de formilaire pour l'entité Technology
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
    $builder
        ->add('name', TextType::class)
        ->add('description', TextareaType::class, array('required' => false))
        ->add('image', FileType::class)
        ->add('category', EntityType::class, array(
            'class'         => 'App:TechnologyCategory',
            'choice_label'  => 'name',
            'multiple'      => false
        ))
        ->add('stack', ChoiceType::class, array(
            'choices'  => array(
                'Front-End' => 'Front-End',
                'Back-End' => 'Back-End',
            ),
        ))
        ->add('mastered', CheckboxType::class, array('required' =>false))
        ->add('published', CheckboxType::class, array('required' => false))
        ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
        'data_class' => Technology::class,
        ));
    }
}