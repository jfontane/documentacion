<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Visita
 *
 *
 * @ORM\Entity
 */
class UsuarioDocumento
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Documento", inversedBy="documentos", cascade={"persist"})
   * @ORM\JoinColumn(name="documento_id", referencedColumnName="id")
   */
  private $documento;

  /**
   * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="documentos", cascade={"persist"})
   * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
   */
  private $usuario;

  /**
   * @ORM\Column(type="integer")
   */
  private $cantidadVisitas = 0;

  /**
   * @ORM\Column(type="datetime", nullable=true)
   */
  private $fechaUltimaVisita;


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
     * Set cantidadVisitas
     *
     * @param integer $cantidadVisitas
     *
     * @return UsuarioDocumento
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
     * Set fechaUltimaVisita
     *
     * @param \DateTime $fechaUltimaVisita
     *
     * @return UsuarioDocumento
     */
    public function setFechaUltimaVisita($fechaUltimaVisita)
    {
        $this->fechaUltimaVisita = $fechaUltimaVisita;

        return $this;
    }

    /**
     * Get fechaUltimaVisita
     *
     * @return \DateTime
     */
    public function getFechaUltimaVisita()
    {
        return $this->fechaUltimaVisita;
    }

    /**
     * Set documento
     *
     * @param \DocumentacionBundle\Entity\Documento $documento
     *
     * @return UsuarioDocumento
     */
    public function setDocumento(\DocumentacionBundle\Entity\Documento $documento = null)
    {
        $this->documento = $documento;

        return $this;
    }

    /**
     * Get documento
     *
     * @return \DocumentacionBundle\Entity\Documento
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * Set usuario
     *
     * @param \DocumentacionBundle\Entity\Usuario $usuario
     *
     * @return UsuarioDocumento
     */
    public function setUsuario(\DocumentacionBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \DocumentacionBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
