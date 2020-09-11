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
use Jubilaciones\DeclaracionesBundle\Controller\AbstractBaseController;
use Jubilaciones\DeclaracionesBundle\Form\ImportacionType;
use Jubilaciones\DeclaracionesBundle\Entity\Importacion;
use Jubilaciones\DeclaracionesBundle\Entity\User;
use Jubilaciones\DeclaracionesBundle\Entity\Organismo;
use Jubilaciones\DeclaracionesBundle\Entity\Representante;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota;

class AdminImportacionController extends Controller {

    public function listarAction() {
        $em = $this->getDoctrine()->getManager();
        $archivos = $em->getRepository('DocumentacionBundle:Importacion')->findAll();
        //dump($usuarios);die;
        return $this->render('@JubilacionesDeclaraciones/AdminImportacion/listar.html.twig', array(
                    'archivos' => $archivos
        ));
    }

    public function nuevoAction(Request $request) {
        $user = $this->getUser();
        $importacion = new Importacion();
        $form = $this->createForm(ImportacionType::class, $importacion, array('bandera'=> !$user->hasRole('ROLE_ADMIN')))
                ->add('Guardar', SubmitType::class);

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

            $em = $this->getDoctrine()->getManager();
            if ($nombre_archivo=='Impagos') {
              $file_name = "impa3.txt";
              $importacion->setProcesado('Si');
            } else {
              //$file_name = $importacion->getId().'_'.$importacion->getNombre() . ".txt";
              $importacion->setProcesado('No');
            };

            $em->persist($importacion);
            $em->flush();

            $file_name = $importacion->getId().'_'.$importacion->getNombre() . ".txt";
            $fileImportacion->move("uploads", $file_name);

            // maybe set a "flash" success message for the user
            // Mensaje para notificar al usuario que todo ha salido bien
            AbstractBaseController::addInfoMessage('El Archivo de importacion "' . $importacion->getNombre() . '"  sido Creado.');
            return $this->redirectToRoute('admin_importacion_listar');
        }
        return $this->render('@JubilacionesDeclaraciones/AdminImportacion/nuevo.html.twig', array('form' => $form->createView()
        ));
    }

    public function importarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $importacion = $em->getRepository('JubilacionesDeclaracionesBundle:Importacion')->findOneBy(array('id' => $id));
        $tipo_importacion = $importacion->getNombre();
        $esta_procesado = $importacion->getProcesado();

        $fileName = $id.'_'.$importacion->getNombre() . '.txt';
        //dump($esta_procesado);die;
        if ($esta_procesado == 'No') {
            if ($tipo_importacion == 'Usuarios') {
                $this->importarUsuarios($fileName);
                $this->vincularUsuariosOrganismos($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La importacion de Usuarios se ha realizado con Exito.');
            } else if ($tipo_importacion == 'Organismos') {
                $this->importarOrganismos($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La importacion de Organismos se ha realizado con Exito.');
            } else if ($tipo_importacion == 'Representantes') {
                $this->importarRepresentantes($fileName);
                $this->vincularOrganismosRepresentantes($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La importacion de Representantes se ha realizado con Exito.');
            } else if ($tipo_importacion == 'Declaraciones_Organismo') {
                $this->vincularDeclaracionesOrganismos($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La Vinculacion de Usuarios y Organismos se ha realizado con Exito.');
            } else if ($tipo_importacion == 'ConvenioCuotas_Organismo') {
                $this->vincularConvenioCuotasOrganismos($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La Carga de ConvenioCuotas se ha realizado con Exito.');
            } else if ($tipo_importacion == 'ConvenioCtaCte_Organismo') {
                $this->cargarConvenioCtaCte($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La Carga de ConvenioCtaCte se ha realizado con Exito.');
            }
        } else
            AbstractBaseController::addInfoMessage('No se ha realizado ninguna importacion con Exito.');

        return $this->redirectToRoute('admin_importacion_listar');
    }

    public function borrarAction($id) {
        $em = $this->getDoctrine()->getManager();
        $importacion = $em->getRepository('JubilacionesDeclaracionesBundle:Importacion')->findOneBy(array('id' => $id));
        $fileName = $id.'_'.$importacion->getNombre() . '.txt';
        // Para borrar el archivo
        $fs = new Filesystem();
        if ($importacion->getNombre()=='Impagos') {
           $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/impa3.txt');
        } else {
           $fs->remove($this->get('kernel')->getRootDir() . '/../web/uploads/'.$fileName);
        }


        $em->remove($importacion);
        $em->flush();
        AbstractBaseController::addWarnMessage('La Importacion de ' .
                $importacion->getNombre() .
                ' se ha borrado correctamente.');

        return $this->redirect($this->generateUrl('admin_importacion_listar'));
    }

    private function importarUsuarios($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
//  dump($archivo[0]);die;

        $passwordEncoder = $this->get('security.password_encoder');
        //$linea=explode(';',$archivo[0]);
        //dump($linea);die;
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < $lineas; $i++) {
            $usuario = new User();
            $username = explode(';', $archivo[$i])[0];
            $password = explode(';', $archivo[$i])[1];
            //dump($username.'-'.$password);die;
            $usuario->setUsername($username);
            $usuario->setPlainPassword($password);
            $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            $usuario->setRoles('ROLE_USER');

            //dump('Password: '.$password);die;
            // 4) save the User!
            $em->persist($usuario);
            $em->flush();
        };
    }

    private function importarOrganismos($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        //  dump($archivo[0]);die;
//  $linea=explode(';',$archivo[0]);
        //dump($linea);die;
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < $lineas; $i++) {
            $organismo = new Organismo();
            $org_codigo = explode(';', $archivo[$i])[0];
            $org_nombre = explode(';', $archivo[$i])[1];
            $org_domicilio_calle = explode(';', $archivo[$i])[2];
            $org_domicilio_numero = explode(';', $archivo[$i])[3];
            $org_localidad = explode(';', $archivo[$i])[4];
            $org_codigo_postal = explode(';', $archivo[$i])[5];
            $org_departamento = explode(';', $archivo[$i])[6];
            $org_telefono_caracteristica = explode(';', $archivo[$i])[7];
            $org_telefono_numero = explode(';', $archivo[$i])[8];
            $org_cuit = explode(';', $archivo[$i])[9];
            $org_habilitado = explode(';', $archivo[$i])[10];
            $org_amparo = explode(';', $archivo[$i])[11];
            $org_email = explode(';', $archivo[$i])[12];
            $org_zona = explode(';', $archivo[$i])[13];

            $organismo->setCodigo($org_codigo);
            $organismo->setNombre($org_nombre);
            $organismo->setDomicilioCalle($org_domicilio_calle);
            $organismo->setDomicilioNumero($org_domicilio_numero);
            $organismo->setLocalidad($org_localidad);
            $organismo->setCodigoPostal($org_codigo_postal);
            $organismo->setDepartamento($org_departamento);
            $organismo->setTelefonoCaracteristica($org_telefono_caracteristica);
            $organismo->setTelefonoNumero($org_telefono_numero);
            $organismo->setCuit($org_cuit);
            $organismo->setHabilitado($org_habilitado);
            $organismo->setAmparo($org_amparo);
            $organismo->setEmail($org_email);
            $organismo->setZona($org_zona);

            //dump('Password: '.$password);die;
            // 4) save the User!
            $em->persist($organismo);
            $em->flush();
            //die;
        };
    }

    private function importarRepresentantes($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        //  dump($archivo[0]);die;
//  $linea=explode(';',$archivo[0]);
        //dump($linea);die;
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < $lineas; $i++) {
            //$rep_codigo=explode(';',$archivo[$i])[0];
            $rep_cuil = explode(';', $archivo[$i])[1];
            $rep = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findOneBy(array('cuil' => $rep_cuil));
            if (null == $rep) {
                $representante = new Representante;
                //$organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $org_codigo));

                $rep_apellido = explode(';', $archivo[$i])[2];
                $rep_nombre = explode(';', $archivo[$i])[3];
                $rep_sexo = explode(';', $archivo[$i])[4];
                $rep_email = explode(';', $archivo[$i])[5];
                $rep_confirmo_datos = explode(';', $archivo[$i])[7];

                $representante->setCuil($rep_cuil);
                $representante->setApellido($rep_apellido);
                $representante->setNombres($rep_nombre);
                $representante->setSexo($rep_sexo);
                $representante->setEmail($rep_email);
                $representante->setConfirmoDatos($rep_confirmo_datos);
                //dump('Password: '.$password);die;
                // 4) save the User!
                $em->persist($representante);
                $em->flush();
            };
            //die;
        };
    }

    private function vincularOrganismosRepresentantes($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < $lineas; $i++) {
            $codigo_organismo = explode(';', $archivo[$i])[0];
            $cuil = explode(';', $archivo[$i])[1];
            $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $codigo_organismo));
            $representante = $em->getRepository('JubilacionesDeclaracionesBundle:Representante')->findOneBy(array('cuil' => $cuil));
            if (null != $representante && null != $organismo) {
                $organismo->setRepresentante($representante);
                $em->persist($organismo);
                $em->flush();
            };
        };
    }

    private function vincularUsuariosOrganismos($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < $lineas; $i++) {
            $codigo_organismo = explode(';', $archivo[$i])[0];
            $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $codigo_organismo));
            $usuario = $em->getRepository('JubilacionesDeclaracionesBundle:User')->findOneBy(array('username' => $codigo_organismo));
            if (null != $usuario && null != $organismo) {
                $usuario->setOrganismo($organismo);
                $em->persist($usuario);
                $em->flush();
            };
        };
    }

    private function vincularDeclaracionesOrganismos($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        $codigo_organismo_actual = 0;

        //$codigo_organismo_obtenido = explode(';', $archivo[0])[0];
        //dump($codigo_organismo_actual.'-'.$codigo_organismo_proximo);die;
        $em = $this->getDoctrine()->getManager();
        $organismo = new Organismo;
        //dump($organismo);die;
        for ($i = 0; $i < $lineas; $i++) {
            $codigo_organismo_obtenido = explode(';', $archivo[$i])[0];
            $declaracion_periodo_anio = explode(';', $archivo[$i])[1];
            $declaracion_periodo_mes = explode(';', $archivo[$i])[2];
            $declaracion_tipo_liquidacion = explode(';', $archivo[$i])[3];
            $declaracion_fecha_entrega = new \DateTime(explode(';', $archivo[$i])[4]);
            $declaracion_fecha_ingreso = explode(';', $archivo[$i])[5];
            $fecha_ingreso = ($declaracion_fecha_ingreso != 'NULL') ? new \DateTime($declaracion_fecha_ingreso) : '';
            $declaracion_estado = explode(';', $archivo[$i])[6];
            $dj_estado="";
            if ($declaracion_estado=='C') {
              $dj_estado='Aprobada';
            } else if ($declaracion_estado=='I') {
              $dj_estado='Rechazada';
            } else if ($declaracion_estado=='P') {
              $dj_estado='Procesando';
            };

            $declaracion = new Declaracionjurada;
            $declaracion->setPeriodoAnio($declaracion_periodo_anio);
            $declaracion->setPeriodoMes($declaracion_periodo_mes);
            $declaracion->setTipoLiquidacion($declaracion_tipo_liquidacion);
            $declaracion->setFechaEntrega($declaracion_fecha_entrega);
            if ($fecha_ingreso) $declaracion->setFechaIngreso($fecha_ingreso);
            $declaracion->setEstado($dj_estado);

            if ($codigo_organismo_actual == $codigo_organismo_obtenido) {
                $declaracion->setOrganismo($organismo);
                $em->persist($declaracion);
                $em->flush();
            } else {
                $codigo_organismo_actual = $codigo_organismo_obtenido;
                $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $codigo_organismo_actual));
                $declaracion->setOrganismo($organismo);
                $em->persist($declaracion);
                $em->flush();
            }
        }; // END for
    }

    /*
      private function vincularConvenioCuotasOrganismos($fileName) {
      $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
      $lineas = count($archivo);
      $codigo_organismo_actual = 0;

      //$codigo_organismo_obtenido = explode(';', $archivo[0])[0];
      //dump($codigo_organismo_actual.'-'.$codigo_organismo_proximo);die;
      $em = $this->getDoctrine()->getManager();
      $organismo = new Organismo;
      //dump($organismo);die;
      for ($i = 0; $i < $lineas; $i++) {
      $codigo_organismo_obtenido = explode(';', $archivo[$i])[0];
      $conve_nro_convenio = explode(';', $archivo[$i])[1];
      $conve_tramo = explode(';', $archivo[$i])[2];
      $conve_nro_cuota = explode(';', $archivo[$i])[3];
      $conve_pagado = explode(';', $archivo[$i])[4];
      $conve_vencimiento1 = explode(';', $archivo[$i])[5];
      $conve_importe1 = explode(';', $archivo[$i])[6];
      $conve_vencimiento2 = explode(';', $archivo[$i])[7];
      $conve_importe2 = explode(';', $archivo[$i])[8];
      $conve_vencimiento3 = explode(';', $archivo[$i])[9];
      $conve_importe3 = explode(';', $archivo[$i])[10];

      $fecha_1_venc = new \DateTime($conve_vencimiento1);
      $fecha_2_venc = new \DateTime($conve_vencimiento2);
      $fecha_3_venc = ($conve_vencimiento3 != 'NULL') ? new \DateTime($conve_vencimiento3) : '';

      $convenio = new Conveniocuota;
      $convenio->setCodigoConvenio($conve_nro_convenio);
      $convenio->setTramo($conve_tramo);
      $convenio->setCuota($conve_nro_cuota);
      $convenio->setPagado($conve_pagado);
      $convenio->setVencimiento1($fecha_1_venc);
      $convenio->setImporte1($conve_importe1);
      $convenio->setVencimiento2($fecha_2_venc);
      $convenio->setImporte2($conve_importe2);
      $convenio->setVencimiento3($fecha_3_venc);
      $convenio->setImporte3($conve_importe3);
      //            if ($fecha_ingreso)
      //                $declaracion->setFechaIngreso($fecha_ingreso);
      //            $declaracion->setEstado($declaracion_estado);

      if ($codigo_organismo_actual == $codigo_organismo_obtenido) {
      $convenio->setOrganismo($organismo);
      $em->persist($convenio);
      $em->flush();
      } else {
      $codigo_organismo_actual = $codigo_organismo_obtenido;
      $organismo = $em->getRepository('JubilacionesDeclaracionesBundle:Organismo')->findOneBy(array('codigo' => $codigo_organismo_actual));
      $convenio->setOrganismo($organismo);
      $em->persist($convenio);
      $em->flush();
      }
      }; // END for
      }
     */

    private function vincularConvenioCuotasOrganismos($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);


        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();

        for ($i = 0; $i < $lineas; $i++) {
            $conve_nro_convenio = explode(';', $archivo[$i])[1];
            $conve_tramo = explode(';', $archivo[$i])[2];
            $conve_nro_cuota = explode(';', $archivo[$i])[3];
            $conve_pagado = (explode(';', $archivo[$i])[4])==""?0:(explode(';', $archivo[$i])[4]);
            $conve_vencimiento1 = explode(';', $archivo[$i])[5];
            $conve_importe1 = explode(';', $archivo[$i])[6];
            $conve_vencimiento2 = explode(';', $archivo[$i])[7];
            $conve_importe2 = explode(';', $archivo[$i])[8];
            $conve_vencimiento3 = explode(';', $archivo[$i])[9];
            $conve_importe3 = explode(';', $archivo[$i])[10];
            $codigo_organismo_obtenido = explode(';', $archivo[$i])[0];
            $query = "INSERT INTO conveniocuota (codigo_convenio, tramo, cuota, pagado, vencimiento1, importe1, vencimiento2, importe2, vencimiento3, importe3, codigo_organismo)
                     VALUES ('$conve_nro_convenio', '$conve_tramo', '$conve_nro_cuota', $conve_pagado, '$conve_vencimiento1', $conve_importe1, '$conve_vencimiento2', $conve_importe2, "
                    . "'$conve_vencimiento3', $conve_importe3, '$codigo_organismo_obtenido')";

            $stmt = $db->prepare($query);
            $params = array();
            $stmt->execute($params);
        }; // END for
    }


    private function cargarConvenioCtaCte($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);


        $em = $this->getDoctrine()->getManager();
        $db = $em->getConnection();

        for ($i = 0; $i < $lineas; $i++) {
            $conve_nro_convenio = explode(';', $archivo[$i])[0];
            $codigo_organismo = explode(';', $archivo[$i])[1];
            $conve_tramo = explode(';', $archivo[$i])[2];
            $conve_nro_cuota = explode(';', $archivo[$i])[3];
            $conve_tipo_movimiento = explode(';', $archivo[$i])[4];
            $conve_fecha_movimiento = explode(';', $archivo[$i])[5];
            $conve_fecha_pago = explode(';', $archivo[$i])[6];
            $conve_fecha_vencimiento = explode(';', $archivo[$i])[7];
            $conve_codigo_movimiento = explode(';', $archivo[$i])[8];
            $conve_importe = explode(';', $archivo[$i])[9];
            $conve_saldo = explode(';', $archivo[$i])[10];

            $query="INSERT INTO conveniocuentacorriente (codigo_convenio, codigo_organismo, tramo, cuota, tipo_movimiento, "
                    . "fecha_movimiento, fecha_pago, fecha_vencimiento, codigo_movimiento, importe, saldo) "
                   . "VALUES ('$conve_nro_convenio', '$codigo_organismo', '$conve_tramo', '$conve_nro_cuota', '$conve_tipo_movimiento', '$conve_fecha_movimiento', "
                   . "'$conve_fecha_pago', '$conve_fecha_vencimiento', '$conve_codigo_movimiento', $conve_importe, $conve_saldo);";

            $stmt = $db->prepare($query);
            $params = array();
            $stmt->execute($params);
        }; // END for
    }

}
