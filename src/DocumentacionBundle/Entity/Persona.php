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
  private $apellido;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $nombre;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $cuil;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $email;


  /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="Representado", mappedBy="persona")
     */
  private $representados;


  /**
   * Many Personas have Many Documentos.
   * @ORM\ManyToMany(targetEntity="Documento", mappedBy="personas")
   */
  private $documentos;


  public function __construct() {
        $this->representados = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentos = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
