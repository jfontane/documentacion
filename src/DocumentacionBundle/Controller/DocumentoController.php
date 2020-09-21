<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class DocumentoController extends Controller
{
    public function listarDocumentosAction()
    {
      $em = $this->getDoctrine()->getManager();
      $documentos = $em->getRepository('DocumentacionBundle:Documento')->findAll();
      $cantidad_documentos = count($documentos);
      //dump($cantidad_documentos);die;

      return $this->render('@Documentacion\Documento\listar.html.twig', array('documentos'=>$documentos));
    }


    public function getArchivoAction($id) {
            $em = $this->getDoctrine()->getManager();
            $documento = $em->getRepository('DocumentacionBundle:Documento')->find($id);
            $fileName = $documento->getArchivo();
            // Para borrar el archivo
            //    $fs = new Filesystem();
            //    $fs->remove($this->get('kernel')->getRootDir().'/../web/uploads/'.$file_name);
            $arch = new File($this->get('kernel')->getRootDir() . '/../web/upload/' . $fileName);
            return $this->file($arch, $fileName);


            /* $file = stream_get_contents($declaracion->getJubidat(), -1, 0);
              //dump(strlen($file));die;
              $size = strlen($file);

              $response = new Response($file, 200, array(
              'Content-Type' => 'application/octet-stream',
              'Content-Length' => $size,
              'Content-Disposition' => 'attachment; filename="jubi.dat"',
              ));
              return $response; */
        }

        public function descargarAction($id)
        {
              $em = $this->getDoctrine()->getManager();
              $documento = $em->getRepository('DocumentacionBundle:Documento')->find($id);
              $fileName = $documento->getArchivo();
              $path = $this->get('kernel')->getRootDir(). "/../web/downloads/";
              $content = file_get_contents($path.$fileName);
              $response = new Response();
              //set headers
              $response->headers->set('Content-Type', 'mime/type');
              $response->headers->set('Content-Disposition', 'attachment;filename="'.$fileName);
              $response->setContent($content);
              if ($response != NULL) {
                $user = $this->getUser();
                if($user->hasRole('ROLE_USER')){
                    $documento->setCantidadVisitas($documento->getCantidadVisitas()+1);
                    $em->persist($documento);
                    $em->flush();
               }
              };
              return $response;
        }

        public function vaciarPeriodoAction($anio,$mes)
        {
              $em = $this->getDoctrine()->getManager();
              $documento = $em->getRepository('DocumentacionBundle:Documento')->findby(array('periodoAnio' => $anio, 'periodoMes' => $mes));
              dump($documento);die;

/*
$fs = new Filesystem();
$fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubidat);
$fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileNamejubi1ind);

if (null == $declaracion) {
    throw $this->createNotFoundException('No existe la Declaracion solicitada.');
}
$em->remove($declaracion);
$em->flush();
AbstractBaseController::addWarnMessage('La Declaracion del Periodo ' .
        $declaracion->getPeriodoAnio() . '/' .
        $declaracion->getPeriodoMes() .
        ' se ha borrado correctamente.');
*/

              //$fileName = $documento->getArchivo();
              /*
              $path = $this->get('kernel')->getRootDir(). "/../web/downloads/";
              $content = file_get_contents($path.$fileName);
              $response = new Response();
              //set headers
              $response->headers->set('Content-Type', 'mime/type');
              $response->headers->set('Content-Disposition', 'attachment;filename="'.$fileName);
              $response->setContent($content);
              if ($response != NULL) {
                $user = $this->getUser();
                if($user->hasRole('ROLE_USER')){
                    $documento->setCantidadVisitas($documento->getCantidadVisitas()+1);
                    $em->persist($documento);
                    $em->flush();
               }
              };
              return $response; */
        }



}
