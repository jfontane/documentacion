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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ImportacionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
      //  dump(ini_get('upload_max_filesize'));exit;
      $tam_max_filesize = ini_get('upload_max_filesize');
      $tam_max_upload_post = ini_get('post_max_size');
      $tam_final = $tam_max_upload_post;


      //if ($tam_max_filesize<=$tam_max_upload_post ) $tam_final = $tam_max_filesize;
      //else $tam_final = $tam_max_upload_post;

        $builder->add('nombre', ChoiceType::class, array(
                    'choices' => array('Inclusion' => 'Inclusion', 'Pasivos' => 'Pasivos', 'Tramites' => 'Tramites' )))
                ->add('archivo', FileType::class,array(
                     'label' => "Archivo",
                     'mapped' => false,
                     'constraints' => [
                   new File([
                       'maxSize' => $tam_final,
                       ])
               ],))
               ->add('periodoAnio',IntegerType::class)
               ->add('periodoMes', ChoiceType::class, array(
                   'choices' => array('Ene' => '01', 'Feb' => '02',
                       'Mar' => '03', 'Abr' => '04',
                       'May' => '05', 'Jun' => '06',
                       'Jul' => '07', 'Ago' => '08',
                       'Sep' => '09', 'Oct' => '10',
                       'Nov' => '11', 'Dic' => '12')
               ))
               ->add('descripcion', TextType::class);

        //->add('Guardar',SubmitType::class, array('label' => 'Nuevo Usuario'));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => 'DocumentacionBundle\Entity\Importacion','bandera' => false));
    }

}
