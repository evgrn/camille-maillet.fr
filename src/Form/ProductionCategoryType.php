<?php

namespace App\Form;

use App\Entity\ProductionCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class ProductionCategoryType
 * @package App\Form
 *
 * Classe contenant le constructeur du formulaire de l'entité ProductionCategory
 */
class ProductionCategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Constructeur du formulaire de l'entité ProductionCategory
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
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
            'data_class' => ProductionCategory::class,
        ]);
    }
}
