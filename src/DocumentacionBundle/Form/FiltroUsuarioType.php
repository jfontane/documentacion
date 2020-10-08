<?php

namespace DocumentacionBundle\Form;

use DocumentacionBundle\Entity\Documento;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FiltroUsuarioType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('usuario', EmailType::class, array('required'=> false));
        $builder->add('cuil', TextType::class, array('required'=> false));
        $builder->add('descripcion', TextType::class, array('required'=> false));

        // =================================================================================
        //Periodo año
        $rango = range(2011, date('Y')); // de 2015 al año actual
        //       $periodo_anio = array('No aplicar');
        $periodo_anio = array_merge(array(0 => 'No aplicar'), $rango);

        //dump($periodo_anio);exit;

        $builder->add('periodoAnio', ChoiceType::class,
                array('choices' => array_flip($periodo_anio), 'data' => 0));
      // =================================================================================
        //Periodo mes
        $periodo_mes = array(
            'No aplicar' => 0,
            'Ene' => '01', 'Feb' => '02', 'Mar' => '03',
            'Abr' => '04', 'May' => '05', 'Jun' => '06',
            'Jul' => '07', 'Ago' => '08', 'Sep' => '09',
            'Oct' => '10', 'Nov' => '11', 'Dic' => '12');
        $builder->add('periodoMes', ChoiceType::class, array(
                    'choices' => $periodo_mes,
                    'data' => 0
        ));
        $builder->add('submit', SubmitType::class, array(
            'label' => 'Filtrar',
            'attr' => array('class' => 'btn btn-success')
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array('data_class' => null));
    }

}
