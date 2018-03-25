<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


/**
 * Class IrlEditType
 * @package App\Form
 *
 * Classe contenant le constructeur du formulaire d'édition de l'entité Irl
 */
class IrlEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Constructeur du formulaire d'édition de l'entité Irl
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->get('image')->setRequired(false);
    }


    /**
     * @return null|string
     */
    public function getParent()
    {
        return IrlType::class;
    }
}
