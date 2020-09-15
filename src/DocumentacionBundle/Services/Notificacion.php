<?php
namespace DocumentacionBundle\Services;

use Swift_Message;

class Notificacion {
    private $doctrine;
    private $mailer;

    function __construct($doctrine, $mailer) {
        $this->doctrine = $doctrine;
        $this->mailer = $mailer;
    }

    public function sendToAll($titulo, $descripion) {
        $em = $this->doctrine->getManager();

        $usuarios = $em->getRepository('CursoSymfonyEventosBundle:Usuario')
                ->findBy(array('notificaciones' => TRUE));

       // if (count($usuarios) > 0) {

            $emails = array();
            /*foreach ($usuarios as $usuario) {
                $emails[] = $usuario->getEmail();
            }*/
            $emails[] = 'jfontanellaz@gmail.com';
            $mensaje = Swift_Message::newInstance()
                    ->setSubject($titulo)
                    ->setFrom(array('no_reply@escuela40.net' => 'Curso Symfony'))
                    ->setBcc($emails)
                    ->setBody($descripion);

            $this->mailer->send($mensaje);
       // }
    }

}
