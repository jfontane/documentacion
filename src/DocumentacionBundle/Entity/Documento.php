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
   */
  private $descripcion;


  /**
   * @ORM\Column(type="string", length=255)
   */
  private $periodoAnio;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $periodoMes;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $cuil;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $cantidadVisitas = 0;

  /**
   * @ORM\Column(type="string", length=255, columnDefinition="enum('Si', 'No')")
   */
  private $activo;

  /**
   * Many Documentos have Many Usuarios.
   * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="documentos")
   * @ORM\JoinTable(name="documentos_usuarios")
   */
  private $usuarios;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->usuarios = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Add usuario
     *
     * @param \DocumentacionBundle\Entity\Usuario $usuario
     *
     * @return Documento
     */
    public function addUsuario(\DocumentacionBundle\Entity\Usuario $usuario)
    {
        $this->usuarios[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario
     *
     * @param \DocumentacionBundle\Entity\Usuario $usuario
     */
    public function removeUsuario(\DocumentacionBundle\Entity\Usuario $usuario)
    {
        $this->usuarios->removeElement($usuario);
    }

    /**
     * Get usuarios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarios()
    {
        return $this->usuarios;
    }

    /**
     * Set activo
     *
     * @param string $activo
     *
     * @return Documento
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return string
     */
    public function getActivo()
    {
        return $this->activo;
    }
}
