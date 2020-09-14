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
  private $archivo;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $descripcion;


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
  private $cuil;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $cantidadVisitas = 0;

  /**
   * Many Documentos have Many Personas.
   * @ORM\ManyToMany(targetEntity="Persona", inversedBy="documentos")
   * @ORM\JoinTable(name="documentos_personas")
   */
  private $personas;

  public function __construct() {
          $this->personas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set archivo
     *
     * @param string $archivo
     *
     * @return Documento
     */
    public function setArchivo($archivo)
    {
        $this->archivo = $archivo;

        return $this;
    }

    /**
     * Get archivo
     *
     * @return string
     */
    public function getArchivo()
    {
        return $this->archivo;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Documento
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set periodoAnio
     *
     * @param string $periodoAnio
     *
     * @return Documento
     */
    public function setPeriodoAnio($periodoAnio)
    {
        $this->periodoAnio = $periodoAnio;

        return $this;
    }

    /**
     * Get periodoAnio
     *
     * @return string
     */
    public function getPeriodoAnio()
    {
        return $this->periodoAnio;
    }

    /**
     * Set periodoMes
     *
     * @param string $periodoMes
     *
     * @return Documento
     */
    public function setPeriodoMes($periodoMes)
    {
        $this->periodoMes = $periodoMes;

        return $this;
    }

    /**
     * Get periodoMes
     *
     * @return string
     */
    public function getPeriodoMes()
    {
        return $this->periodoMes;
    }

    /**
     * Set cuil
     *
     * @param string $cuil
     *
     * @return Documento
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
     * Add persona
     *
     * @param \DocumentacionBundle\Entity\Persona $persona
     *
     * @return Documento
     */
    public function addPersona(\DocumentacionBundle\Entity\Persona $persona)
    {
        $this->personas[] = $persona;

        return $this;
    }

    /**
     * Remove persona
     *
     * @param \DocumentacionBundle\Entity\Persona $persona
     */
    public function removePersona(\DocumentacionBundle\Entity\Persona $persona)
    {
        $this->personas->removeElement($persona);
    }

    /**
     * Get personas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPersonas()
    {
        return $this->personas;
    }

    /**
     * Set cantidadVisitas
     *
     * @param integer $cantidadVisitas
     *
     * @return Documento
     */
    public function setCantidadVisitas($cantidadVisitas)
    {
        $this->cantidadVisitas = $cantidadVisitas;

        return $this;
    }

    /**
     * Get cantidadVisitas
     *
     * @return integer
     */
    public function getCantidadVisitas()
    {
        return $this->cantidadVisitas;
    }
}
