<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;


class UsuarioController extends Controller
{
    public function documentosAction($id)
    {

      $em = $this->getDoctrine()->getManager();
      $usuario = $em->getRepository('DocumentacionBundle:Usuario')->findOneById($id);
      $documentos = $usuario->getDocumentos();
      //dump();die;
      return $this->render('@Documentacion\Usuario\documentos.html.twig', array(
                           'documentos' => $documentos,
                           'usuario' => $usuario
                          )
                         );
    }

    public function listarAction(Request $request)
    {
      $em = $this->getDoctrine()->getManager();
      $usuarios = $em->getRepository('DocumentacionBundle:Usuario')->findAll();
      //$documentos = $persona->getDocumentos();
      //dump($personas);die;
      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
                     $usuarios,
                     $request->query->getInt('page', 1),
                     10
             );

      return $this->render('@Documentacion\Usuario\listar.html.twig', array(
                           'pagination' => $pagination
                          )
                         );
    }
}
