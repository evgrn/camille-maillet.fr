<?php

namespace App\Form;

use App\Entity\TechnologyCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TechnologyCategoryType
 * @package App\Form
 * Classe contenant le constructeur du formulaire de l'entité TechnologyType
 */
class TechnologyCategoryType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * onstructeur du formulaire de l'entité TechnologyType
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Valider'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TechnologyCategory::class,
        ]);
    }
}
