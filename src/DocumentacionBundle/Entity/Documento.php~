<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Documento
 *
 * @ORM\Entity
 */
class Documento
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
  private $nombre;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $periodoAnio;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $periodoMes;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $descripcion;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $cuil;

  /**
   * Many Documentos have Many Personas.
   * @ORM\ManyToMany(targetEntity="Persona", inversedBy="documentos")
   * @ORM\JoinTable(name="documentos_personas")
   */
  private $personas;

  public function __construct() {
          $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
  }

}
