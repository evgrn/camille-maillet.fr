<?php
namespace App\Form;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class TechnologyEditType
 * @package App\Form
 *
 * Classe contenant le constructeur de formulaire d'édition de l'entité Technology
 */
class TechnologyEditType extends TechnologyType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Constructeur de formulaire d'édition de l'entité Technology
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->get('image')
            ->setRequired(false);
        ;
    }

    /**
     * @return null|string
     */
    public function getParent()
    {
        return TechnologyType::class;
    }
}

