<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;


class ConfiguracionController extends Controller {

    public function configuracionAction(UserInterface $usuario) {
      //dump($usuario);die;
      if ($usuario->hasRole('ROLE_ADMIN')) {
        return $this->render('@Documentacion\Configuracion\configuracion.html.twig');
      } else {
        AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                . '" no puede realizar esta operacion.');
        return $this->render('@Documentacion\Default\error.html.twig');
      };
    }

}
