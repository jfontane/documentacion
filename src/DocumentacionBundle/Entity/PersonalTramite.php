<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PersonalTramite
 *
 * @ORM\Entity
 */
class PersonalTramite
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
  private $numeroExpediente;

  /**
   * @ORM\Column(type="string", length=255)
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
   * @ORM\Column(type="string", length=255)
   * @Assert\NotBlank
   */
  private $tipoBeneficio;



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
     * @return PersonalTramite
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
     * Set numeroExpediente
     *
     * @param string $numeroExpediente
     *
     * @return PersonalTramite
     */
    public function setNumeroExpediente($numeroExpediente)
    {
        $this->numeroExpediente = $numeroExpediente;

        return $this;
    }

    /**
     * Get numeroExpediente
     *
     * @return string
     */
    public function getNumeroExpediente()
    {
        return $this->numeroExpediente;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return PersonalTramite
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
     * @return PersonalTramite
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
     * @return PersonalTramite
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
     * @return PersonalTramite
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
     * Set tipoBeneficio
     *
     * @param string $tipoBeneficio
     *
     * @return PersonalTramite
     */
    public function setTipoBeneficio($tipoBeneficio)
    {
        $this->tipoBeneficio = $tipoBeneficio;

        return $this;
    }

    /**
     * Get tipoBeneficio
     *
     * @return string
     */
    public function getTipoBeneficio()
    {
        return $this->tipoBeneficio;
    }
}
