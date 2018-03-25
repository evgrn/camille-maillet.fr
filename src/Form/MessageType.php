<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * Class MessageType
 * @package App\Form
 *
 * Classe contenant le constructeur du formulaire de l'entité Message
 */
class MessageType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * Constructeur du formulaire de l'entité Message
     */
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('author', TextType::class)
            ->add('email', EmailType::class)
            ->add('tel', TelType::class , array('required' => false))
            ->add('object', TextType::class)
            ->add('message', TextareaType::class, array('required' => false))
            ->add('submit', SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class
        ]);
    }
}
