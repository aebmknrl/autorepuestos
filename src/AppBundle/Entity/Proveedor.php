<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Proveedor
 *
 * @ORM\Table(name="proveedor", uniqueConstraints={@ORM\UniqueConstraint(name="PROV_NOMBRE_UNIQUE", columns={"PROV_NOMBRE"})})
 * @ORM\Entity
 */
class Proveedor
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PROV_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $provId;

    /**
     * @var string
     *
     * @ORM\Column(name="PROV_NOMBRE", type="string", length=45, nullable=false)
     */
    private $provNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="PROV_DIRECCION", type="string", length=45, nullable=false)
     */
    private $provDireccion;

    /**
     * @var string
     *
     * @ORM\Column(name="PROV_RIF", type="string", length=45, nullable=true)
     */
    private $provRif;

    /**
     * @var string
     *
     * @ORM\Column(name="PROV_STATUS", type="string", length=45, nullable=false)
     */
    private $provStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="PROV_OBSERVACION", type="string", length=45, nullable=true)
     */
    private $provObservacion;



    /**
     * Get provId
     *
     * @return integer
     */
    public function getProvId()
    {
        return $this->provId;
    }

    /**
     * Set provNombre
     *
     * @param string $provNombre
     *
     * @return Proveedor
     */
    public function setProvNombre($provNombre)
    {
        $this->provNombre = $provNombre;

        return $this;
    }

    /**
     * Get provNombre
     *
     * @return string
     */
    public function getProvNombre()
    {
        return $this->provNombre;
    }

    /**
     * Set provDireccion
     *
     * @param string $provDireccion
     *
     * @return Proveedor
     */
    public function setProvDireccion($provDireccion)
    {
        $this->provDireccion = $provDireccion;

        return $this;
    }

    /**
     * Get provDireccion
     *
     * @return string
     */
    public function getProvDireccion()
    {
        return $this->provDireccion;
    }

    /**
     * Set provRif
     *
     * @param string $provRif
     *
     * @return Proveedor
     */
    public function setProvRif($provRif)
    {
        $this->provRif = $provRif;

        return $this;
    }

    /**
     * Get provRif
     *
     * @return string
     */
    public function getProvRif()
    {
        return $this->provRif;
    }

    /**
     * Set provStatus
     *
     * @param string $provStatus
     *
     * @return Proveedor
     */
    public function setProvStatus($provStatus)
    {
        $this->provStatus = $provStatus;

        return $this;
    }

    /**
     * Get provStatus
     *
     * @return string
     */
    public function getProvStatus()
    {
        return $this->provStatus;
    }

    /**
     * Set provObservacion
     *
     * @param string $provObservacion
     *
     * @return Proveedor
     */
    public function setProvObservacion($provObservacion)
    {
        $this->provObservacion = $provObservacion;

        return $this;
    }

    /**
     * Get provObservacion
     *
     * @return string
     */
    public function getProvObservacion()
    {
        return $this->provObservacion;
    }
}
