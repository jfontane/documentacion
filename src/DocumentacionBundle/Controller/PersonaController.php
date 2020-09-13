<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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

    public function listarAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $personas = $em->getRepository('DocumentacionBundle:Persona')->findAll();
      //$documentos = $persona->getDocumentos();
      //dump($personas);die;
      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
                     $personas,
                     $request->query->getInt('page', 1),
                     10
             );

      return $this->render('@Documentacion\Persona\listar.html.twig', array(
                           'pagination' => $pagination
                          )
                         );
    }
}
