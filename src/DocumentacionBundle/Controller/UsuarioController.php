<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use DocumentacionBundle\Entity\Usuario;
use DocumentacionBundle\Form\UsuarioType;
use Symfony\Component\Security\Core\User\UserInterface;


class UsuarioController extends Controller
{
    public function documentosAction(Request $request)
    {
      $user = $this->getUser();
      if($user->hasRole('ROLE_USER')){
         //dump($user->getId());die;
         $em = $this->getDoctrine()->getManager();
         $usuario = $em->getRepository('DocumentacionBundle:Usuario')->findOneById($user->getId());
         $cantidadDocumentos = count($usuario->getDocumentos());

         return $this->render('@Documentacion\Usuario\ulistar.html.twig', array(
                             'usuario' => $usuario,
                             'cantidad'=> $cantidadDocumentos
                            )
        );
      }
    }

      public function crearAdminAction($email,$autorizacion)
      {

        if($autorizacion=='1q2w3e4r5t'){
           //dump($user->getId());die;
           $em = $this->getDoctrine()->getManager();
           $passwordEncoder = $this->get('security.password_encoder');
           $usuario = new Usuario();
           $usuario->setUsername($email);
           $usuario->setPlainPassword('12345');
           $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
           $usuario->setPassword($password);
           $usuario->setRoles('ROLE_ADMIN');
           $usuario->setFechaExpiracion(new \DateTime('now'));
           $em->persist($usuario);
           $em->flush();
           AbstractBaseController::addWarnMessage('El Usuario '.$email.' se ha Creado Exitosamente');
        } else {
          AbstractBaseController::addWarnMessage('El Usuario '.$email.' NO se ha Creado');
        }
        return $this->redirectToRoute('principal_logueado');
    }

    public function listarAction(Request $request)
    {
      $user = $this->getUser();
      if($user->hasRole('ROLE_ADMIN')) {
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


    public function editarAction(Request $request, UserInterface $user) {
      $passwordEncoder = $this->get('security.password_encoder');
      $form = $this->createForm(UserType::class, $user)
      ->add('Guardar', SubmitType::class);
      $form->remove('roles');$form->remove('zona');$form->remove('username');
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        AbstractBaseController::addWarnMessage('El Usuario "' . $user->getUsername()
        . '" se ha modificado correctamente.');
        //  $this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha actualizado el evento '.$organismo->getNombre().'.');
        return $this->redirect($this->generateUrl('principal_logueado'));
      }
      return $this->render('@Documentacion/Usuario/editar.html.twig'
      , array('form' => $form->createView(), 'usuario' => $user
    ));
    }


    public function editarEmailAction($id, Request $request, UserInterface $user) {
      $em = $this->getDoctrine()->getManager();
      $usuario = $em->getRepository('DocumentacionBundle:Usuario')->findOneById($id);
      //dump($usuario);die;
      $form = $this->createForm(UsuarioType::class, $usuario, array('require_plainPassword'=> false));
      $form->remove('plainPassword');
      $form->remove('roles');
      $form->remove('fechaExpiracion');
      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        //die('entrooo');
      /*  //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
        $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
*/      //$form->get('dueDate')->getData();
        //$form->get('roles')->setData($usuario->getRoles());
        $em->persist($usuario);
        $em->flush();
        AbstractBaseController::addWarnMessage('El Email del Usuario "' . $usuario->getUsername(). '", se ha modificado correctamente.');
        //  $this->get('eventos.notificacion')->sendToAll('Symfony 2020!', 'Se ha actualizado el evento '.$organismo->getNombre().'.');
        return $this->redirect($this->generateUrl('admin_usuarios_listar'));
      };
      return $this->render('@Documentacion/Usuario/editarEmail.html.twig', array(
                             'form' => $form->createView(),
                             'usuario' => $usuario));
      //                       die;
    }

}
