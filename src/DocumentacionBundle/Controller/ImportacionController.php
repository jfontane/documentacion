<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\FormError;
use DocumentacionBundle\Controller\AbstractBaseController;
use DocumentacionBundle\Form\ImportacionType;
use DocumentacionBundle\Entity\Importacion;
use DocumentacionBundle\Entity\User;

class ImportacionController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $archivos = $em->getRepository('DocumentacionBundle:Importacion')->findAll();
        //dump($usuarios);die;
        return $this->render('@Documentacion/Importacion/listar.html.twig', array(
                    'archivos' => $archivos
        ));
    }

    public function borrarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $importacion = $em->getRepository('DocumentacionBundle:Importacion')->findOneBy(array('id' => $id));
        $fileName = $id.'_'.$importacion->getNombre() . '.txt';
        // Para borrar el archivo
        $fs = new Filesystem();
        if ($importacion->getNombre()=='Impagos') {
           //$fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/impa3.txt');
        } else {
           //$fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/'.$fileName);
        }


        $em->remove($importacion);
        $em->flush();
        AbstractBaseController::addWarnMessage('La Importacion de ' .
                $importacion->getNombre() .
                ' se ha borrado correctamente.');

        return $this->redirect($this->generateUrl('admin_importacion_listar'));
    }


}
