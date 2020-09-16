<?php

namespace DocumentacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class Usuario implements UserInterface, \Serializable {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank
     */
    private $fechaExpiracion;

    /**
     * Many Personas have Many Documentos.
     * @ORM\ManyToMany(targetEntity="Documento", mappedBy="usuarios")
     */
    private $documentos;



    public function __construct() {
    }

    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    public function setPlainPassword($password) {
        $this->plainPassword = $password;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getSalt() {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function setRoles($roles)
    {
      $this->roles = [$roles];
      return $this;
    }

    public function getRoles() {
        return $this->roles;
    }

    public function hasRole($rol) {
      return in_array($rol,$this->roles);
    }

    public function eraseCredentials() {

    }

    public function __toString() {
        return $this->getUsername();
    }


    /**
     * Set fechaExpiracion
     *
     * @param \DateTime $fechaExpiracion
     *
     * @return Usuario
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;

        return $this;
    }

    /**
     * Get fechaExpiracion
     *
     * @return \DateTime
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
     * @return Usuario
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

    /** @see \Serializable::serialize() */
   public function serialize()
   {
       return serialize(array(
           $this->id,
           $this->username,
           $this->password,
           // ver la sección salt debajo
           // $this->salt,
       ));
   }

   /** @see \Serializable::unserialize() */
   public function unserialize($serialized)
   {
       list (
           $this->id,
           $this->username,
           $this->password,
           // ver la sección salt debajo
           // $this->salt
       ) = unserialize($serialized);
   }
}
