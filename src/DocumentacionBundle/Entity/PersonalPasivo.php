<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PersonalPasivo
 *
 * @ORM\Entity
 */
class PersonalPasivo
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
  private $cuip;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $clase;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $numero;

  /**
   * @ORM\Column(type="string", length=1)
   * @Assert\NotBlank
   */
  private $digito;

  /**
   * @ORM\Column(type="string", length=1)
   * @Assert\NotBlank
   */
  private $sexo;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $apellidoPaterno;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $apellidoMaterno;

  /**
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $nombres;

  /**
   * @ORM\Column(type="date")
   */
  private $inclusion;



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
     * Set cuip
     *
     * @param string $cuip
     *
     * @return PersonalPasivo
     */
    public function setCuip($cuip)
    {
        $this->cuip = $cuip;

        return $this;
    }

    /**
     * Get cuip
     *
     * @return string
     */
    public function getCuip()
    {
        return $this->cuip;
    }

    /**
     * Set clase
     *
     * @param string $clase
     *
     * @return PersonalPasivo
     */
    public function setClase($clase)
    {
        $this->clase = $clase;

        return $this;
    }

    /**
     * Get clase
     *
     * @return string
     */
    public function getClase()
    {
        return $this->clase;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return PersonalPasivo
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set digito
     *
     * @param string $digito
     *
     * @return PersonalPasivo
     */
    public function setDigito($digito)
    {
        $this->digito = $digito;

        return $this;
    }

    /**
     * Get digito
     *
     * @return string
     */
    public function getDigito()
    {
        return $this->digito;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return PersonalPasivo
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set apellidoPaterno
     *
     * @param string $apellidoPaterno
     *
     * @return PersonalPasivo
     */
    public function setApellidoPaterno($apellidoPaterno)
    {
        $this->apellidoPaterno = $apellidoPaterno;

        return $this;
    }

    /**
     * Get apellidoPaterno
     *
     * @return string
     */
    public function getApellidoPaterno()
    {
        return $this->apellidoPaterno;
    }

    /**
     * Set apellidoMaterno
     *
     * @param string $apellidoMaterno
     *
     * @return PersonalPasivo
     */
    public function setApellidoMaterno($apellidoMaterno)
    {
        $this->apellidoMaterno = $apellidoMaterno;

        return $this;
    }

    /**
     * Get apellidoMaterno
     *
     * @return string
     */
    public function getApellidoMaterno()
    {
        return $this->apellidoMaterno;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     *
     * @return PersonalPasivo
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set inclusion
     *
     * @param \DateTime $inclusion
     *
     * @return PersonalPasivo
     */
    public function setInclusion($inclusion)
    {
        $this->inclusion = $inclusion;

        return $this;
    }

    /**
     * Get inclusion
     *
     * @return \DateTime
     */
    public function getInclusion()
    {
        return $this->inclusion;
    }

    /**
     * Set documentoTipo
     *
     * @param string $documentoTipo
     *
     * @return PersonalPasivo
     */
    public function setDocumentoTipo($documentoTipo)
    {
        $this->documentoTipo = $documentoTipo;

        return $this;
    }

    /**
     * Get documentoTipo
     *
     * @return string
     */
    public function getDocumentoTipo()
    {
        return $this->documentoTipo;
    }

    /**
     * Set documentoNumero
     *
     * @param string $documentoNumero
     *
     * @return PersonalPasivo
     */
    public function setDocumentoNumero($documentoNumero)
    {
        $this->documentoNumero = $documentoNumero;

        return $this;
    }

    /**
     * Get documentoNumero
     *
     * @return string
     */
    public function getDocumentoNumero()
    {
        return $this->documentoNumero;
    }
}
