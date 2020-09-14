<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Jubilaciones\DeclaracionesBundle\Entity\Representante;
use Jubilaciones\DeclaracionesBundle\Form\RepresentanteType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('@Documentacion\Default\index.html.twig');
    }

     public function principalAction() {
        return $this->render('@Documentacion\Default\principal.html.twig');
    }


}
