<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Persona
 *
 * @ORM\Entity
 */
class Persona
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $email;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $password;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $fechaExpiracion;

  /**
   * Many Personas have Many Documentos.
   * @ORM\ManyToMany(targetEntity="Documento", mappedBy="personas")
   */
  private $documentos;


  public function __construct() {
        $this->documentos = new \Doctrine\Common\Collections\ArrayCollection();
    }


}
