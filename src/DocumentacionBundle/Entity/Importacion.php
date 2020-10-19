<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Organismo
 *
 * @ORM\Table(name="importacion")
 * @ORM\Entity(repositoryClass="DocumentacionBundle\Repository\ImportacionRepository")
 */
class Importacion {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, columnDefinition="enum('Inclusion','Pasivos','Tramites')")
     * @Assert\NotBlank
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer")
     */
    private $periodoAnio;

    /**
     * @ORM\Column(type="integer")
     */
    private $periodoMes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')")
     * @Assert\Choice({"Si","No"})
     */
    protected $procesado;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombreUsuario;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Importacion
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Importacion
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Importacion
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set procesado
     *
     * @param string $procesado
     *
     * @return Importacion
     */
    public function setProcesado($procesado)
    {
        $this->procesado = $procesado;

        return $this;
    }

    /**
     * Get procesado
     *
     * @return string
     */
    public function getProcesado()
    {
        return $this->procesado;
    }

    /**
     * Set nombreUsuario
     *
     * @param string $nombreUsuario
     *
     * @return Importacion
     */
    public function setNombreUsuario($nombreUsuario)
    {
        $this->nombreUsuario = $nombreUsuario;

        return $this;
    }

    /**
     * Get nombreUsuario
     *
     * @return string
     */
    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    /**
     * Set periodoAnio
     *
     * @param string $periodoAnio
     *
     * @return Importacion
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
     * @return Importacion
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
}
