<?php

namespace Jubilaciones\DeclaracionesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\UserType;
use Jubilaciones\DeclaracionesBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UsuarioController extends Controller {

  public function listarAdminAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $usuarios = $em->getRepository('JubilacionesDeclaracionesBundle:User')->findAll();

      //dump($usuarios[0]->getRoles());die;

      $paginator = $this->get('knp_paginator');
      $pagination = $paginator->paginate(
                     $usuarios,
                     $request->query->getInt('page', 1),
                     10
             );

      //dump($pagination);die;
      return $this->render('@JubilacionesDeclaraciones/Usuario/listarAdmin.html.twig', array(
                  'pagination' => $pagination
      ));
  }

  public function listarContralorAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    //$usuarios = $em->getRepository('JubilacionesDeclaracionesBundle:User')->findAll();

    $dql = "SELECT u
    FROM JubilacionesDeclaracionesBundle:User u
    ORDER BY u.username Desc";
    $usuarios = $em->createQuery($dql)->getResult();


    $paginator = $this->get('knp_paginator');
    $pagination = $paginator->paginate(
      $usuarios,
      $request->query->getInt('page', 1),
      10
    );

    //dump($pagination);die;
    return $this->render('@JubilacionesDeclaraciones/Usuario/listarContralor.html.twig', array(
      'pagination' => $pagination
    ));
  }


  public function nuevoAdminAction(Request $request) {
      $passwordEncoder = $this->get('security.password_encoder');
      $usuario = new User();
      $form = $this->createForm(UserType::class, $usuario);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
        // 3) Encode the password (you could also do this via Doctrine listener)
          $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
          $usuario->setPassword($password);
          //$rol[0] = $form->get('roles')->getData();
          $roles=$form->get('roles')->getData();
          $usuario->setRoles($roles);
          // 4) save the User!
          $em = $this->getDoctrine()->getManager();
          $em->persist($usuario);
          $em->flush();

          // ... do any other work - like sending them an email, etc
          // maybe set a "flash" success message for the user

          // Mensaje para notificar al usuario que todo ha salido bien
          AbstractBaseController::addInfoMessage('El Usuario <b>' .$usuario->getUsername() .' </b> sido Creado.');
          return $this->redirectToRoute('admin_usuario_listar');
      }
      return $this->render('@JubilacionesDeclaraciones/Usuario/nuevoAdmin.html.twig', array('form' => $form->createView()
      ));
  }


public function editarOrganismoAction(Request $request, UserInterface $user) {
  //dump($user);die;

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
  return $this->render('@JubilacionesDeclaraciones/Usuario/editarOrganismo.html.twig'
  , array('form' => $form->createView(), 'usuario' => $user
));
}

public function editarContralorAction(Request $request, $id) {
  $passwordEncoder = $this->get('security.password_encoder');
  $em = $this->getDoctrine()->getManager();
  if (null == $usuario = $em->find('JubilacionesDeclaracionesBundle:User', $id)) {
    throw $this->createNotFoundException('No existe el Usuario solicitado.');
  }
  $form = $this->createForm(UserType::class, $usuario)
  ->add('Guardar', SubmitType::class);
  $form->remove('roles');$form->remove('zona');$form->remove('username');
  $form->handleRequest($request);
  if ($form->isSubmitted() && $form->isValid()) {
    //$evento->setDescripcion($this->get('eventos.util')->autoLinkText($evento->getDescripcion()));
    $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
    $usuario->setPassword($password);
    $em->persist($usuario);
    $em->flush();
    AbstractBaseController::addWarnMessage('El Usuario "' . $usuario->getUsername()
    . '" se ha modificado correctamente.');
    return $this->redirect($this->generateUrl('contralor_usuario_listar'));
  }
  return $this->render('@JubilacionesDeclaraciones/Usuario/editarContralor.html.twig'
  , array('form' => $form->createView(), 'usuario' => $usuario
));
}

public function borrarAdminAction($id) {
  $em = $this->getDoctrine()->getManager();
  $usuario = $em->getRepository('JubilacionesDeclaracionesBundle:User')->findOneBy(array('id' => $id));

  $em->remove($usuario);
  $em->flush();
  AbstractBaseController::addInfoMessage('El Usuario ' .
  $usuario .
  ' se ha borrado correctamente.');
  return $this->redirect($this->generateUrl('admin_usuario_listar'));
}




}
