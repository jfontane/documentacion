<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Persona
 *
 * @ORM\Entity
 */
class Representado
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Persona", inversedBy="representados")
     * @ORM\JoinColumn(name="persona_id", referencedColumnName="id")
     */
    private $persona;




}
