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
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $password;

  /**
   * One Category has Many Categories.
   * @ORM\OneToMany(targetEntity="Persona", mappedBy="parent")
   */
  private $representados;

  /**
   * Many Categories have One Category.
   * @ORM\ManyToOne(targetEntity="Persona", inversedBy="representados")
   * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
   */
  private $parent;

  /**
   * Many Personas have Many Documentos.
   * @ORM\ManyToMany(targetEntity="Documento", mappedBy="personas")
   */
  private $documentos;


  public function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->documentos = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
