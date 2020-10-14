<?php

namespace DocumentacionBundle\Form;

use DocumentacionBundle\Entity\Documento;
use DocumentacionBundle\Entity\Usuario;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FiltroUsuarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('username', EmailType::class, array('required'=> false));
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Filtrar',
            'attr' => array('class' => 'btn btn-success')
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array('data_class' => null));
    }

}
