<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PersonaController extends Controller
{

    public function listarDocumentosPorEmailAction($email)
    {
      $em = $this->getDoctrine()->getManager();
      $documentos = $em->getRepository('DocumentacionBundle:Persona')->findBy(array('email'=>$email));
      $cantidad_documentos = count($documentos[0]->getDocumentos());
      for ($c = 0;$c < $cantidad_documentos;$c++) {
        dump($documentos[0]->getDocumentos()[$c]->getArchivo());
      };
      die;
/*
      $em = $this->getDoctrine()->getManager();
      $representados = $em->getRepository('DocumentacionBundle:Persona')->findBy(array('cuil'=>'20249128342'));
      dump(count($representados));die;*/
      return $this->render('@Documentacion\Default\index.html.twig');
    }




}
