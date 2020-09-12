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
use DocumentacionBundle\Entity\Persona;
use DocumentacionBundle\Entity\Documento;
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

    public function nuevoAction(Request $request) {
      //  $user = $this->getUser();
        $importacion = new Importacion();
        $form = $this->createForm(ImportacionType::class, $importacion, array('bandera'=> true))
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

            $importacion->setProcesado('No');
            $em = $this->getDoctrine()->getManager();
            $em->persist($importacion);
            $em->flush();

            $file_name = $importacion->getId().'_'.$importacion->getNombre() . ".txt";
            $fileImportacion->move("uploads", $file_name);
            // maybe set a "flash" success message for the user
            // Mensaje para notificar al usuario que todo ha salido bien
            AbstractBaseController::addWarnMessage('El Archivo de importacion "' . $importacion->getNombre() . '"  sido Creado.');
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
        $fileName = $id.'_'.$importacion->getNombre() . '.txt';
        if ($esta_procesado == 'No') {
            if ($tipo_importacion == 'Beneficios') {
              //  $this->importarUsuarios($fileName);
              //  $this->vincularUsuariosOrganismos($fileName);
                $this->procesarArchivo($fileName);
                $importacion->setProcesado('Si');
                $em->persist($importacion);
                $em->flush();
                AbstractBaseController::addInfoMessage('La importacion de Beneficios se ha realizado con Exito.');
            }
        } else
            AbstractBaseController::addInfoMessage('No se ha realizado ninguna importacion con Exito.');

        return $this->redirectToRoute('admin_importacion_listar');
    }

    private function procesarArchivo($fileName) {
        $archivo = file($this->get('kernel')->getRootDir() . '/../web/uploads/' . $fileName);
        $lineas = count($archivo);
        //  dump($archivo[0]);die;
        //  $linea=explode(';',$archivo[0]);
        $em = $this->getDoctrine()->getManager();
        for ($i = 0; $i < $lineas; $i++) {
            $documento = new Documento();
            $persona = new Persona();
            $cuil = explode(';', $archivo[$i])[0];
            $nombre_archivo = explode(';', $archivo[$i])[1];
            $descripcion = explode(';', $archivo[$i])[2];
            $email = explode(';', $archivo[$i])[3];
            $email_arreglado = str_replace (array("\r\n", "\n", "\r"), '', $email);
            $documento->setCuil($cuil);
            $documento->setArchivo($nombre_archivo);
            $documento->setDescripcion($descripcion);
            $documento->setPeriodoAnio('2020');
            $documento->setPeriodoMes('08');
            $persona->setEmail($email_arreglado);
            $persona->setPassword('12345');
            $persona->setFechaExpiracion(new \DateTime('now'));
            if ($this->personaEsta($email_arreglado)==NULL) {
               $em->persist($persona);
            }
            $em->persist($documento);
            $em->flush();
            /*
            if ($this->documentoEsta($nombre_archivo)==NULL){
              $documento->addPersona($persona);
              $em->persist($persona);
              $em->persist($documento);
              $em->flush();
            } else {
              $documento->addPersona($persona);
              $em->persist($persona);
              $em->persist($documento);
              $em->flush();
            }
            */




            //dump($cuil.'-'.$nombre_archivo.'-'.$tipo.'-'.$email_arreglado);
            //$persona = $this->personaEsta($email_arreglado);
            //$documentos = $this->documentoEsta($cuil);
            //dump($cuil,$documentos);


          /*  $organismo->setCodigo($org_codigo);
            $organismo->setNombre($org_nombre);
            $organismo->setDomicilioCalle($org_domicilio_calle);
            $organismo->setDomicilioNumero($org_domicilio_numero);

            //dump('Password: '.$password);die;
            // 4) save the User!
            $em->persist($organismo);
            $em->flush();
            //die;*/
        };
        die;
    }

    private function personaEsta($email) {
      $em = $this->getDoctrine()->getManager();
      $persona = $em->getRepository('DocumentacionBundle:Persona')->findOneBy(array('email' => $email));
      return $persona;
    }

    private function documentoEsta($archivo) {
      $em = $this->getDoctrine()->getManager();
      $documento = $em->getRepository('DocumentacionBundle:Documento')->findOneBy(array('archivo' => $archivo));
      return $documento;
    }


}
