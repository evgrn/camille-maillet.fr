<?php

namespace App\Form;
use Symfony\Component\Form\FormBuilderInterface;

class ProductionEditType extends ProductionType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder->get('image')->setRequired(false);
    $builder->get('thumbnail')->setRequired(false);


    ;
    }


    public function getParent()
    {
    return ProductionType::class;
    }
}