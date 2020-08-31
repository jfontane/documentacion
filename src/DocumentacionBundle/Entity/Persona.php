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


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Persona
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Persona
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set cuil
     *
     * @param string $cuil
     *
     * @return Persona
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;

        return $this;
    }

    /**
     * Get cuil
     *
     * @return string
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Persona
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Persona
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add representado
     *
     * @param \DocumentacionBundle\Entity\Persona $representado
     *
     * @return Persona
     */
    public function addRepresentado(\DocumentacionBundle\Entity\Persona $representado)
    {
        $this->representados[] = $representado;

        return $this;
    }

    /**
     * Remove representado
     *
     * @param \DocumentacionBundle\Entity\Persona $representado
     */
    public function removeRepresentado(\DocumentacionBundle\Entity\Persona $representado)
    {
        $this->representados->removeElement($representado);
    }

    /**
     * Get representados
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepresentados()
    {
        return $this->representados;
    }

    /**
     * Set parent
     *
     * @param \DocumentacionBundle\Entity\Persona $parent
     *
     * @return Persona
     */
    public function setParent(\DocumentacionBundle\Entity\Persona $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \DocumentacionBundle\Entity\Persona
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add documento
     *
     * @param \DocumentacionBundle\Entity\Documento $documento
     *
     * @return Persona
     */
    public function addDocumento(\DocumentacionBundle\Entity\Documento $documento)
    {
        $this->documentos[] = $documento;

        return $this;
    }

    /**
     * Remove documento
     *
     * @param \DocumentacionBundle\Entity\Documento $documento
     */
    public function removeDocumento(\DocumentacionBundle\Entity\Documento $documento)
    {
        $this->documentos->removeElement($documento);
    }

    /**
     * Get documentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentos()
    {
        return $this->documentos;
    }
}
