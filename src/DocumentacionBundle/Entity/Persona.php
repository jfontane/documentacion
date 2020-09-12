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
   * @ORM\Column(type="datetime", nullable=true)
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
     * Set fechaExpiracion
     *
     * @param string $fechaExpiracion
     *
     * @return Persona
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;

        return $this;
    }

    /**
     * Get fechaExpiracion
     *
     * @return string
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
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
