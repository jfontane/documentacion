<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PersonaController extends Controller
{
    public function documentosAction($id)
    {

      $em = $this->getDoctrine()->getManager();
      $persona = $em->getRepository('DocumentacionBundle:Persona')->findOneById($id);
      $documentos = $persona->getDocumentos();
      //dump();die;
      return $this->render('@Documentacion\Persona\documentos.html.twig', array(
                           'documentos' => $documentos,
                           'persona' => $persona
                          )
                         );
    }
}
