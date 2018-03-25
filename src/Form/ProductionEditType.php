<?php

namespace App\Form;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class ProductionEditType
 * @package App\Form
 *
 * Classe contenant le constructeur du formulaire d'édition de l'entité Production
 */
class ProductionEditType extends ProductionType
{

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->get('image')->setRequired(false);
        $builder->get('thumbnail')->setRequired(false);
        $builder->get('preview')->setRequired(false);
        ;
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
    return ProductionType::class;
    }
}