<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PersonaController extends Controller
{
    public function indexAction()
    {

      $em = $this->getDoctrine()->getManager();
      $representados = $em->getRepository('DocumentacionBundle:Persona')->findBy(array('cuil'=>'20249128342'));
      dump(count($representados));die;
      return $this->render('@Documentacion\Default\index.html.twig');
    }
}
