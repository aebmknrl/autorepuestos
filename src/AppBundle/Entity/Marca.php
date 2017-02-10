<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Marca
 *
 * @ORM\Table(name="marca", uniqueConstraints={@ORM\UniqueConstraint(name="MAR_ID_UNIQUE", columns={"MAR_ID"}), @ORM\UniqueConstraint(name="MAR_NOMBRE_UNIQUE", columns={"MAR_NOMBRE"})})
 * @ORM\Entity
 */
class Marca
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MAR_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $marId;

    /**
     * @var string
     *
     * @ORM\Column(name="MAR_NOMBRE", type="string", length=45, nullable=false)
     */
    private $marNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="MAR_OBSERVACION", type="string", length=45, nullable=true)
     */
    private $marObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="MAR_IMAGEN", type="string", length=250, nullable=true)
     */
    private $marImagen;



    /**
     * Get marId
     *
     * @return integer
     */
    public function getMarId()
    {
        return $this->marId;
    }

    /**
     * Set marNombre
     *
     * @param string $marNombre
     *
     * @return Marca
     */
    public function setMarNombre($marNombre)
    {
        $this->marNombre = $marNombre;

        return $this;
    }

    /**
     * Get marNombre
     *
     * @return string
     */
    public function getMarNombre()
    {
        return $this->marNombre;
    }

    /**
     * Set marObservacion
     *
     * @param string $marObservacion
     *
     * @return Marca
     */
    public function setMarObservacion($marObservacion)
    {
        $this->marObservacion = $marObservacion;

        return $this;
    }

    /**
     * Get marObservacion
     *
     * @return string
     */
    public function getMarObservacion()
    {
        return $this->marObservacion;
    }

    /**
     * Set marImagen
     *
     * @param string $marImagen
     *
     * @return Marca
     */
    public function setMarImagen($marImagen)
    {
        $this->marImagen = $marImagen;

        return $this;
    }

    /**
     * Get marImagen
     *
     * @return string
     */
    public function getMarImagen()
    {
        return $this->marImagen;
    }
}
