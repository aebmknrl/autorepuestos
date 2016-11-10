<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fabricante
 *
 * @ORM\Table(name="fabricante", uniqueConstraints={@ORM\UniqueConstraint(name="FAB_ID_UNIQUE", columns={"FAB_ID"})})
 * @ORM\Entity
 */
class Fabricante
{
    /**
     * @var integer
     *
     * @ORM\Column(name="FAB_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $fabId;

    /**
     * @var string
     *
     * @ORM\Column(name="FAB_NOMBRE", type="string", length=45, nullable=false)
     */
    private $fabNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="FAB_DESCRIPCION", type="string", length=45, nullable=false)
     */
    private $fabDescripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="FAB_PAIS", type="string", length=45, nullable=false)
     */
    private $fabPais;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FAB_TIEMPO", type="date", nullable=true)
     */
    private $fabTiempo;



    /**
     * Get fabId
     *
     * @return integer
     */
    public function getFabId()
    {
        return $this->fabId;
    }

    /**
     * Set fabNombre
     *
     * @param string $fabNombre
     *
     * @return Fabricante
     */
    public function setFabNombre($fabNombre)
    {
        $this->fabNombre = $fabNombre;

        return $this;
    }

    /**
     * Get fabNombre
     *
     * @return string
     */
    public function getFabNombre()
    {
        return $this->fabNombre;
    }

    /**
     * Set fabDescripcion
     *
     * @param string $fabDescripcion
     *
     * @return Fabricante
     */
    public function setFabDescripcion($fabDescripcion)
    {
        $this->fabDescripcion = $fabDescripcion;

        return $this;
    }

    /**
     * Get fabDescripcion
     *
     * @return string
     */
    public function getFabDescripcion()
    {
        return $this->fabDescripcion;
    }

    /**
     * Set fabPais
     *
     * @param string $fabPais
     *
     * @return Fabricante
     */
    public function setFabPais($fabPais)
    {
        $this->fabPais = $fabPais;

        return $this;
    }

    /**
     * Get fabPais
     *
     * @return string
     */
    public function getFabPais()
    {
        return $this->fabPais;
    }

    /**
     * Set fabTiempo
     *
     * @param \DateTime $fabTiempo
     *
     * @return Fabricante
     */
    public function setFabTiempo($fabTiempo)
    {
        $this->fabTiempo = $fabTiempo;

        return $this;
    }

    /**
     * Get fabTiempo
     *
     * @return \DateTime
     */
    public function getFabTiempo()
    {
        return $this->fabTiempo;
    }
}
