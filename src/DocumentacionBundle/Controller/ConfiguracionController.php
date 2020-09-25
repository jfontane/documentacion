<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Filesystem\Filesystem;

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

    public function vaciarTablasAction(UserInterface $usuario) {
      if ($usuario->hasRole('ROLE_ADMIN')) {
          $em = $this->getDoctrine()->getManager();
          $fs = new Filesystem();
          $documentos = $em->getRepository('DocumentacionBundle:Documento')->findAll();
          foreach ($documentos as $documento) {
              $file_name = $documento->getArchivo();
              $fs->remove($this->get('kernel')->getRootDir() . '/../web/downloads/' . utf8_decode($file_name));
              $em->remove($documento);
              $em->flush();
          }
          $db = $em->getConnection();
          $query = "DELETE FROM usuario WHERE roles NOT LIKE '%ROLE_ADMIN%'";
          $stmt = $db->prepare($query);
          $stmt->execute();
          $query = "TRUNCATE TABLE importacion";
          $stmt = $db->prepare($query);
          $stmt->execute();
          AbstractBaseController::addWarnMessage('Atencion: Las tablas se han vaciado correctamente.');
          return $this->render('@Documentacion\Configuracion\configuracion.html.twig');
      } else {
          AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                  . '" no puede realizar esta operacion.');
          return $this->render('@Documentacion\Default\error.html.twig');
      };
    }





}
