<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $authenticationUtils = $this->get('security.authentication_utils');
        // obtener el error de login si hay
        $error = $authenticationUtils->getLastAuthenticationError();

        // último nombre de usuario introducido por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        '@Documentacion\Default\generarPassword.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );
    }

}
