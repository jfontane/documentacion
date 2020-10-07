<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\UserInterface;
use DocumentacionBundle\Form\FiltroDocumentoType;
use DocumentacionBundle\Services\DocumentosService;




class DocumentoController extends Controller {

    public function listarDocumentosAction(UserInterface $usuario,Request $request) {
      if ($usuario->hasRole('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();


            $formFiltro = $this->createForm(FiltroDocumentoType::class, null, array(
                'method' => 'GET'
            ));
            $formFiltro->handleRequest($request);
            $filtros = array();
            if ($formFiltro->isSubmitted() && $formFiltro->isValid()) {
                $filtros = $formFiltro->getData();
                //dump($filtros);die;
            }

            $documentoService = $this->get(DocumentosService::class);
            $resultado = $documentoService->filtrar($filtros); // ver acaaaaaaaaaaaaaaaa
            $documentos = $resultado->getResult();

            $ARRAY_DOCUMENTOS = array();
            $archivos = scandir('./../web/downloads'); // todos los archivos fisicos en la carpeta downloads
            $cantidad = 0;

            foreach ($documentos as $documento) {
                $ARR_TMP = array();
                $ARR_TMP['id'] = $documento->getId();
                $ARR_TMP['cuil'] = $documento->getCuil();
                $ARR_TMP['archivo'] = $documento->getArchivo();
                $ARR_TMP['periodoAnio'] = $documento->getPeriodoAnio();
                $ARR_TMP['periodoMes'] = $documento->getPeriodoMes();
                $ARR_TMP['descripcion'] = $documento->getDescripcion();
                $ARR_TMP['cantidadVisitas'] = $documento->getCantidadVisitas();
                $cantidad++;
                if ($this->existeArchivo($documento->getArchivo(), $archivos)) {
                    $ARR_TMP['archivoFisico'] = 'Si';
                } else {
                    $ARR_TMP['archivoFisico'] = 'No';
                }
                array_push($ARRAY_DOCUMENTOS, $ARR_TMP);
            }
            $query = $ARRAY_DOCUMENTOS;
            $items_por_pagina = $this->getParameter('knp_paginator_items_por_pagina');
            $paginator = $this->get('knp_paginator');
            $pagination = $paginator->paginate(
                    $query, $request->query->getInt('page', 1), $items_por_pagina
            );

            return $this->render('@Documentacion\Documento\listar.html.twig', array(
                                                       'pagination' => $pagination,
                                                       'cantidad' => $cantidad,
                                                       'form_filtro' => $formFiltro->createView()
                                ));
        } else {
          AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                  . '" no puede realizar esta operacion.');
          return $this->render('@Documentacion\Default\error.html.twig');
        }
    }

    public function descargarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $documento = $em->getRepository('DocumentacionBundle:Documento')->find($id);
        $fileName = utf8_decode($documento->getArchivo());
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


    public function listarArchivosFisicosSobrantesAction(UserInterface $usuario) {
        if ($usuario->hasRole('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $archivos = scandir('./../web/downloads'); // todos los archivos fisicos en la carpeta downloads
            $ARRAY_ARCHIVOS_FISICOS_SOBRANTES = array();
            foreach ($archivos as $archivo) {
                $archivo = utf8_encode($archivo);
                if ($archivo!='.' && $archivo!='..') {
                  $documento = $em->getRepository('DocumentacionBundle:Documento')->findby(array('archivo' => $archivo));
                  if (NULL == $documento) {
                      $ARRAY_ARCHIVOS_FISICOS_SOBRANTES_ITEM = array();
                      $ARRAY_ARCHIVOS_FISICOS_SOBRANTES_ITEM['archivo'] = $archivo;
                      array_push($ARRAY_ARCHIVOS_FISICOS_SOBRANTES,$ARRAY_ARCHIVOS_FISICOS_SOBRANTES_ITEM);
                  }
                }
            }
            $cantidadDocumentos = count($ARRAY_ARCHIVOS_FISICOS_SOBRANTES);
            return $this->render('@Documentacion\Configuracion\listarFisicosSobrantes.html.twig', array(
                                   'documentos' => $ARRAY_ARCHIVOS_FISICOS_SOBRANTES,
                                   'cantidad' => $cantidadDocumentos
            ));
          } else {
            AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                    . '" no puede realizar esta operacion.');
            return $this->render('@Documentacion\Default\error.html.twig');
          }

    }

    public function eliminarArchivoFisicoSobranteAction($nombre, UserInterface $usuario) { // FALTA TERMINA
      if ($usuario->hasRole('ROLE_ADMIN')) {
            $fs = new Filesystem();
            $fs->remove($this->get('kernel')->getRootDir() . '/../web/downloads/' . utf8_decode($nombre));
            AbstractBaseController::addWarnMessage('Atencion: El Archivo "' . $nombre
                    . '" Se ha Eliminado Correctamente.');
            return $this->redirectToRoute('documento_listar_sobrantes');
      } else {
          AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                  . '" no puede realizar esta operacion.');
          return $this->render('@Documentacion\Default\error.html.twig');
      }
    }

    public function eliminarArchivosSobrantesAction(UserInterface $usuario) {
       if ($usuario->hasRole('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $archivos = scandir('./../web/downloads');
            $fs = new Filesystem();
            foreach ($archivos as $archivo) {
               if ($archivo!='.' && $archivo!='..') {
                    $documento = $em->getRepository('DocumentacionBundle:Documento')->findby(array('archivo' => $archivo));
                    if (NULL == $documento) { // SIGNIFICA QUE QUE EL ARCHIVO FISICO NO ESTA EN LA BASE DE DATOS POR LO TANTO SOBRA
                        //dump($archivo);
                        $fs->remove($this->get('kernel')->getRootDir() . '/../web/downloads/' . $archivo);
                    }
              }
            }
            AbstractBaseController::addWarnMessage('Atencion: Los archivos que NO estan vinculados a la Base de Datos se eliminaron correctamente.');
            return $this->redirectToRoute('documento_listar_sobrantes');
        } else {
          AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                  . '" no puede realizar esta operacion.');
          return $this->render('@Documentacion\Default\error.html.twig');
        }
    }


    public function activarDocumentosAction($estado, UserInterface $usuario) {
      if ($usuario->hasRole('ROLE_ADMIN')) {
            if ($estado=='Si') $mensaje = "Se han Activado todos los Documentos.";
            else $mensaje = "Se han Desactivado todos los Documentos.";
            $em = $this->getDoctrine()->getManager();
            $documentos = $em->getRepository('DocumentacionBundle:Documento')->findAll();
            foreach ($documentos as $documento) {
                $documento->setActivo($estado);
                $em->persist($documento);
                $em->flush();
            }
            AbstractBaseController::addWarnMessage('Atencion: '.$mensaje);
            return $this->render('@Documentacion\Configuracion\configuracion.html.twig');
        } else {
          AbstractBaseController::addWarnMessage('Atencion: El usuario "' . $usuario->getUsername()
                  . '" no puede realizar esta operacion.');
          return $this->render('@Documentacion\Default\error.html.twig');
        }
    }



    public function existeArchivo($nombre_archivo, $archivos) {
        $existe = false;
        foreach ($archivos as $archivo) {
                if ($archivo == utf8_decode($nombre_archivo)) {
                $existe = true;
                break;
              } // END IF
        } // END FOREACH
        return $existe;
    }

}
