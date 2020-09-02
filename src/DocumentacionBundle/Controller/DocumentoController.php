<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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




}
