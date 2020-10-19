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
use DocumentacionBundle\Entity\Usuario;
use DocumentacionBundle\Entity\UsuarioDocumento;
use DocumentacionBundle\Entity\Documento;
use DocumentacionBundle\Entity\PersonalPasivo;
use DocumentacionBundle\Entity\PersonalTramite;

class ImportacionController extends Controller {

    public function listarAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $importaciones = $em->getRepository('DocumentacionBundle:Importacion')->findAll();
        $items_por_pagina = $this->getParameter('knp_paginator_items_por_pagina');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $importaciones, $request->query->getInt('page', 1), 15
        );
        return $this->render('@Documentacion/Importacion/listar.html.twig', array(
                    'pagination' => $pagination,
        ));
    }

    public function borrarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $importacion = $em->getRepository('DocumentacionBundle:Importacion')->findOneBy(array('id' => $id));
        $fileName = $id . '_' . $importacion->getNombre() . '.txt';
        $fs = new Filesystem();
        $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $em->remove($importacion);
        $em->flush();
        AbstractBaseController::addWarnMessage('El registro de La Importacion de ' .
                $importacion->getNombre() .
                ' se ha borrado correctamente.');
        return $this->redirect($this->generateUrl('admin_importacion_listar'));
    }

    public function nuevoAction(Request $request) {
        $user = $this->getUser();
        $user_name = $user->getUserName();
        $importacion = new Importacion();
        $form = $this->createForm(ImportacionType::class, $importacion, array('bandera' => true));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            //$password = $passwordEncoder->encodePassword($importacion, $importacion->getPlainPassword());
            //$importacion->setPassword($password);
            //dump('Password: '.$password);die;
            // 4) save the User!
            //Obtenemos el archivo del formulario y lo subimos al servidor
            $fileImportacion = $form->get('archivo')->getData();
            $nombre_archivo = $form->get('nombre')->getData();

            $contenido = file_get_contents($fileImportacion);
            // Le ponemos un nombre al fichero
            $importacion->setFechaCreacion(new \DateTime('now'));

            $importacion->setProcesado('No');
            $importacion->setNombreUsuario($user_name);
            $em = $this->getDoctrine()->getManager();
            $em->persist($importacion);
            $em->flush();

            $file_name = $importacion->getId() . '_' . $importacion->getNombre() . ".txt";
            $fileImportacion->move("uploads", $file_name);
            // maybe set a "flash" success message for the user
            // Mensaje para notificar al usuario que todo ha salido bien
            AbstractBaseController::addWarnMessage('La importacion del archivo "' . $importacion->getNombre() . '"  ha sido Exitosa.');
            return $this->redirectToRoute('admin_importacion_listar');
        }
        return $this->render('@Documentacion/Importacion/nuevo.html.twig', array('form' => $form->createView()
        ));
    }

    public function importarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $importacion = $em->getRepository('DocumentacionBundle:Importacion')->findOneBy(array('id' => $id));
        $tipo_importacion = $importacion->getNombre();
        $esta_procesado = $importacion->getProcesado();
        $fileName = $id . '_' . $importacion->getNombre() . '.txt';
        $periodoAnio = $importacion->getPeriodoAnio();
        $periodoMes_tmp = $importacion->getPeriodoMes();
        $periodoMes = ($periodoMes_tmp > 0 && $periodoMes_tmp < 10) ? ('0' . $periodoMes_tmp) : $periodoMes_tmp;

        if ($esta_procesado == 'No') {
            if ($tipo_importacion == 'Inclusion') {
                $this->procesarArchivoInclusion($fileName, $periodoAnio, $periodoMes);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addWarnMessage('La importacion de Inclusion de Beneficios se ha PROCESADO con Exito.');
            } else if ($tipo_importacion == 'Pasivos') {
                $this->procesarArchivoPasivos($fileName, $periodoAnio, $periodoMes);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addWarnMessage('La importacion de Constancias de Beneficios se ha PROCESADO con Exito.');
            } else if ($tipo_importacion == 'Tramites') {
                $this->procesarArchivoTramites($fileName, $periodoAnio, $periodoMes);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addWarnMessage('La importacion de Tramites Iniciados se ha PROCESADO con Exito.');
            }
        } else
            AbstractBaseController::addWarnMessage('No se ha realizado ninguna importacion con Exito.');
        return $this->redirectToRoute('admin_importacion_listar');
    }

    private function procesarArchivoInclusion($fileName, $periAnio, $periMes) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        $em = $this->getDoctrine()->getManager();
        $passwordEncoder = $this->get('security.password_encoder');
        $c = 0;
        for ($i = 0; $i < $lineas; $i++) {
            $documento = new Documento();
            $usuario = new Usuario();
            $usuario_documento = new UsuarioDocumento();
            $cuil = explode(';', $archivo[$i])[0];
            $nombre_archivo = explode(';', $archivo[$i])[1];
            $descripcion = explode(';', $archivo[$i])[2];
            $email = explode(';', $archivo[$i])[3];
            $email_arreglado = str_replace(array("\r\n", "\n", "\r"), '', $email);

            //BUSCO EL NOMBRE DE ARCHIVO PARA VER SI EXISTE EL DOCUMENTO
            $docu = $em->getRepository('DocumentacionBundle:Documento')->findOneBy(array('archivo' => $nombre_archivo));
            if (NULL == $docu) {
                  $documento->setCuil($cuil);
                  $documento->setArchivo($nombre_archivo);
                  $documento->setDescripcion($descripcion);
                  $documento->setPeriodoAnio($periAnio);
                  $documento->setPeriodoMes($periMes);
                  if ($descripcion == 'RESOLUCIÓN') {
                     $documento->setActivo('Si');
                  } else {
                      $documento->setActivo('Si');
                  }
                  $usuario_documento->setDocumento($documento);
            } else {
              $usuario_documento->setDocumento($docu);
            }
            //BUSCO EL EMAIL PARA VER SI EXISTE EL USUARIO
            $perso = $em->getRepository('DocumentacionBundle:Usuario')->findOneBy(array('username' => $email_arreglado));
            //SI NO EXISTE PERSISTO EL USUARIO Y LA VINCULO AL DOCUMENTO
            if (NULL == $perso) {
                $usuario->setUsername($email_arreglado);
                $usuario->setPlainPassword('12345');
                $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
                $usuario->setPassword($password);
                $usuario->setRoles('ROLE_USER');
                $usuario->setFechaExpiracion(new \DateTime('now'));
                $usuario->setFechaRegistracion(NULL);
                $usuario_documento->setUsuario($usuario);
            } else { // SI YA EXISTE EL USUARIO LA VINCULO AL DOCUMENTO
                //$documento->addUsuario($perso);
                $usuario_documento->setUsuario($perso);
            };

            $em->persist($usuario_documento);
            $em->flush();
        }; // END FOR
        die;
    }



    private function limpiar_metas($string)
    {
      $string = htmlentities($string);
      $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
      return $string;
    }

    private function procesarArchivoPasivos($fileName, $periAnio, $periMes) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $c = 0;$values = "";$query = "";
        for ($i = 0; $i < $lineas; $i++) {
            $c++;
            $cuil = explode(',', $archivo[$i])[0];
            $clase = explode(',', $archivo[$i])[1];
            $numero = explode(',', $archivo[$i])[2];
            $digito = explode(',', $archivo[$i])[3];
            $sexo = explode(',', $archivo[$i])[6];
            $apellidoPaterno = str_replace("'","\\'",explode(',', $archivo[$i])[7]);
            $apellidoMaterno = str_replace("'","\\'",explode(',', $archivo[$i])[8]);
            $nombres = str_replace("'","\\'",explode(',', $archivo[$i])[9]);
            $fechaInclusion = explode(',', $archivo[$i])[10];
            $fechaInclusionCorregida = substr($fechaInclusion,0,4).'-'.substr($fechaInclusion,4,2).'-'.substr($fechaInclusion,6,2);
            $values .= "('$cuil', '$clase', '$numero', '$digito', '$sexo', '$apellidoPaterno', '$apellidoMaterno', '$nombres', '$fechaInclusionCorregida'),";
            $query = "";
            if ($c==500 or $i==$lineas-1) {
               $values = substr($values,0,strlen($values)-1);
               $query = "INSERT INTO personal_pasivo (cuip, clase, numero, digito, sexo, apellido_paterno, apellido_materno, nombres, inclusion) values ".$values;
               $c=0;
               $stmt = $db->prepare($query);
               $stmt->execute();
               $values = "";$query = "";
            }
        }; // END FOR
    }

    private function procesarArchivoTramites($fileName, $periAnio, $periMes) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();
        $c = 0;$values = "";$query = "";
        for ($i = 0; $i < $lineas; $i++) {
            $c++;
            $cuil = explode(',', $archivo[$i])[0];
            $expediente = explode(',', $archivo[$i])[1];
            $sexo = explode(',', $archivo[$i])[4];
            $apellidoPaterno = str_replace("'","\\'",explode(',', $archivo[$i])[5]);
            $apellidoMaterno = str_replace("'","\\'",explode(',', $archivo[$i])[6]);
            $nombres = str_replace("'","\\'",explode(',', $archivo[$i])[7]);
            $tipoBeneficio = str_replace("'","\\'",explode(',', $archivo[$i])[8]);
            $values .= "('$cuil', '$expediente', '$sexo', '$apellidoPaterno', '$apellidoMaterno', '$nombres', '$tipoBeneficio'),";
            $query = "";

            if ($c==500 or $i==$lineas-1) {
               $values = substr($values,0,strlen($values)-1);
               $query = "INSERT INTO personal_tramite (cuip, numero_expediente, sexo, apellido_paterno, apellido_materno, nombres, tipo_beneficio) values ".$values;
               $c=0;
               $stmt = $db->prepare($query);
               $stmt->execute();
               $values = "";$query = "";

            }

        }; // END FOR

    }

}
