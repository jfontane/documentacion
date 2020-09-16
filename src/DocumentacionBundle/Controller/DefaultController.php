<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DocumentacionBundle\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('@Documentacion\Default\index.html.twig');
    }

     public function principalAction() {
        return $this->render('@Documentacion\Default\principal.html.twig');
    }

    public function loginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');
        // obtener el error de login si hay
        $error = $authenticationUtils->getLastAuthenticationError();

        // último nombre de usuario introducido por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        '@Documentacion\Default\login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );
    }

    public function generarPasswordAction(Request $request) {

          $passwordEncoder = $this->get('security.password_encoder');

          $defaultData = array();
          $form = $this->createFormBuilder($defaultData)
                 ->add('username', EmailType::class)
                 ->getForm();

                 $form->handleRequest($request);

           if ($form->isValid()) {
               // Los datos están en un array con los keys "name", "email", y "message"
               $data = $form->getData();
               $username = $data['username'];
               //dump($username);die;
               $em = $this->getDoctrine()->getManager();
               $usuario = $em->getRepository('DocumentacionBundle:Usuario')->findOneBy(array('username' => 'jfontanellaz@gmail.com'));
               dump($usuario->getUsername());die;

           }
          return $this->render('@Documentacion/Default/generarPassword.html.twig', array('form' => $form->createView()
    ));
  }


}
