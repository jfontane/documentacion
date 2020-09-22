<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;

class DocumentoController extends Controller {

    public function listarDocumentosAction() {
        $em = $this->getDoctrine()->getManager();
        $documentos = $em->getRepository('DocumentacionBundle:Documento')->findAll();
        //dump($documentos);die;
        $ARRAY_DOCUMENTOS = array();
        foreach ($documentos as $documento) {
            $ARR_TMP = array();
            $ARR_TMP['id'] = $documento->getId();
            $ARR_TMP['cuil'] = $documento->getCuil();
            $ARR_TMP['archivo'] = $documento->getArchivo();
            $ARR_TMP['periodoAnio'] = $documento->getPeriodoAnio();
            $ARR_TMP['periodoMes'] = $documento->getPeriodoMes();
            $ARR_TMP['descripcion'] = $documento->getDescripcion();
            $ARR_TMP['cantidadVisitas'] = $documento->getCantidadVisitas();
            if ($this->existeArchivo($documento->getArchivo())) {
                $ARR_TMP['archivoFisico'] = 'Si';
            } else {
                $ARR_TMP['archivoFisico'] = 'No';
            }

            array_push($ARRAY_DOCUMENTOS, $ARR_TMP);
        }
        //dump($ARRAY_DOCUMENTOS);die;

        return $this->render('@Documentacion\Documento\listar.html.twig', array('documentos' => $ARRAY_DOCUMENTOS));
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

    public function descargarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $documento = $em->getRepository('DocumentacionBundle:Documento')->find($id);
        $fileName = $documento->getArchivo();
        $path = $this->get('kernel')->getRootDir() . "/../web/downloads/";
        $content = file_get_contents($path . $fileName);
        $response = new Response();
        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName);
        $response->setContent($content);
        if ($response != NULL) {
            $user = $this->getUser();
            if ($user->hasRole('ROLE_USER')) {
                $documento->setCantidadVisitas($documento->getCantidadVisitas() + 1);
                $em->persist($documento);
                $em->flush();
            }
        };
        return $response;
    }

    public function eliminarPeriodoAction($anio, $mes) {
        $fs = new Filesystem();
        $em = $this->getDoctrine()->getManager();
        $documentos = $em->getRepository('DocumentacionBundle:Documento')->findby(array('periodoAnio' => $anio, 'periodoMes' => $mes));
        foreach ($documentos as $documento) {
            $file_name = $documento->getArchivo();
            $fs->remove($this->get('kernel')->getRootDir() . '/../web/downloads/' . $file_name);
            $em->remove($documento);
            $em->flush();
        }

        die;
    }

    public function buscarArchivosSobrantesPorNombreAction() {
        $em = $this->getDoctrine()->getManager();
        $finder = new Finder();
        $finder->files()->in('./../web/downloads');
        foreach ($finder as $file) {
            $file_name = $file->getRelativePathname();
            $documento = $em->getRepository('DocumentacionBundle:Documento')->findby(array('archivo' => $file_name));
            if (NULL == $documento) {
                dump($file_name);
            }
        }
        die;
    }

    public function eliminarArchivosSobrantesPorNombreAction() {
        $em = $this->getDoctrine()->getManager();
        $finder = new Finder();
        $fs = new Filesystem();
        $finder->files()->in('./../web/downloads');
        foreach ($finder as $file) {
            $file_name = $file->getRelativePathname();
            $documento = $em->getRepository('DocumentacionBundle:Documento')->findby(array('archivo' => $file_name));
            if (NULL == $documento) { // SIGNIFICA QUE QUE EL ARCHIVO FISICO NO ESTA EN LA BASE DE DATOS POR LO TANTO SOBRA
                dump($file_name);
                $fs->remove($this->get('kernel')->getRootDir() . '/../web/downloads/' . $file_name);
            }
        }
        die;
    }

    public function existeArchivo($nombre_archivo) {
        $existe = false;
        //dump($nombre_archivo);die;

        $finder = new Finder();
        $finder->files()->in('./../web/downloads');
        //dump($finder);die;
        foreach ($finder as $file) {
            //dump($nombre_archivo);die;
            $file_name = $file->getRelativePathname();
            if ($nombre_archivo == $file_name) {
                $existe = true;
                break;
            }
        }
        return $existe;
    }

}
