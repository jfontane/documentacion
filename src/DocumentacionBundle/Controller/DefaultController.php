<?php

namespace DocumentacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use DocumentacionBundle\Entity\Usuario;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Core\User\UserInterface;


class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('@Documentacion\Default\index.html.twig');
    }

     public function principalAction() {
        return $this->render('@Documentacion\Default\principal.html.twig');
    }

    public function loginAction(Request $request) {
        $authenticationUtils = $this->get('security.authentication_utils');
        // obtener el error de login si hay
        $error = $authenticationUtils->getLastAuthenticationError();

        // último nombre de usuario introducido por el usuario
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
                        '@Documentacion\Default\login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $lastUsername,
                    'error' => $error,
                        )
        );
    }

    public function generarPasswordAction(Request $request) {

          $passwordEncoder = $this->get('security.password_encoder');
          $defaultData = array();
          $form = $this->createFormBuilder($defaultData)
                 ->add('username', EmailType::class)
                 ->getForm();

                 $form->handleRequest($request);

           if ($form->isValid()) {
               // Los datos están en un array con los keys "name", "email", y "message"
               $data = $form->getData();
               $username = $data['username'];
               //dump($username);die;
               $em = $this->getDoctrine()->getManager();
               $usuario = $em->getRepository('DocumentacionBundle:Usuario')->findOneBy(array('username' => $username));

               if (NULL !== $usuario) {
                 //ACA GENERAR LA PASSWORD ALEATORIA Y SETEAR LA DURACION DE 48 hs.
                 $fecha_actual = date("d-m-Y H:i:s");
                 //sumo 2 dias
                 $fecha_expiracion = date("d-m-Y H:i:s",strtotime($fecha_actual."+ 2 day"));
                 $password_aleatoria = $this->generar_password_complejo(10);
                 $password = $passwordEncoder->encodePassword($usuario, '1q2w3e');
                 $usuario->setPassword($password);
                 $usuario->setFechaExpiracion(new \DateTime($fecha_expiracion));
                 $em->persist($usuario);
                 $em->flush();
                 // EN ESTA INSTANCIA SE DEBERA MANDAR EL EMAIL CON LA CLAVE.

                 $this->get('Notificacion')->sendTo('Caja Jubilaciones - Sistema Documentacion Digital - Clave Generada', 'Su Clave de acceso es: '.$password_aleatoria. ' .', $usuario->getUsername());
                 AbstractBaseController::addWarnMessage('La Clave Generada es: '.$password_aleatoria.' ,con un tiempo de Expiracion de 48Hs');
               } else { //SI NO COINCIDE EL EMAIL ENTONCES DAMOS UN MENSAJE POR ABSTRACTBASECONTROLLER
                 AbstractBaseController::addWarnMessage('El email NO se encuentra Registrado.');
               }
               return $this->redirectToRoute('documentacion_homepage');
           }
          return $this->render('@Documentacion/Default/generarPassword.html.twig', array('form' => $form->createView()
    ));
  }

  private function generar_password_complejo($largo){
    $cadena_base =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $cadena_base .= '0123456789' ;
    $password = '';
    $limite = strlen($cadena_base) - 1;
    for ($i=0; $i < $largo; $i++)
      $password .= $cadena_base[rand(0, $limite)];
    return $password;
}

}
