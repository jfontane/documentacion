<?php

namespace DocumentacionBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UsuarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('username', TextType::class)
                ->add('plainPassword', RepeatedType::class, [
                      'type' => PasswordType::class,
                            'first_options' => ['label' => 'Password'],
                            'second_options' => ['label' => 'Repeat Password'],
                        ])
                ->add('roles', ChoiceType::class, array(
                            'mapped' => false,
                            'choices' => array('ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN','ROLE_ADMIN' => 'ROLE_ADMIN', 'ROLE_CONTRALOR' => 'ROLE_CONTRALOR',
                                'ROLE_ORGANISMO' => 'ROLE_ORGANISMO', 'ROLE_USER' => 'ROLE_USER'))
                        );

                //->add('Guardar',SubmitType::class, array('label' => 'Nuevo Usuario'));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => 'DocumentacionBundle\Entity\Usuario'));
    }

}
