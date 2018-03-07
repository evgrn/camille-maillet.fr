<?php
namespace App\Form;
use Symfony\Component\Form\FormBuilderInterface;

class TechnologyEditType extends TechnologyType
{

public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder

    ->get('image')->setRequired(false);

;
}


    public function getParent()
    {
        return TechnologyType::class;
    }
}