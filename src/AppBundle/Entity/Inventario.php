<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inventario
 *
 * @ORM\Table(name="inventario", uniqueConstraints={@ORM\UniqueConstraint(name="INV_ID_UNIQUE", columns={"INV_ID"})}, indexes={@ORM\Index(name="fk_INVENTARIO_PROVEEDOR1_idx", columns={"PROVEEDOR_PROV_ID"}), @ORM\Index(name="fk_INVENTARIO_PARTE1_idx", columns={"PARTE_PAR_ID"})})
 * @ORM\Entity
 */
class Inventario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="INV_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $invId;

    /**
     * @var integer
     *
     * @ORM\Column(name="INV_CANTIDAD", type="integer", nullable=false)
     */
    private $invCantidad;

    /**
     * @var integer
     *
     * @ORM\Column(name="INV_PRECIO", type="integer", nullable=false)
     */
    private $invPrecio;

    /**
     * @var integer
     *
     * @ORM\Column(name="INV_EMPAQUE", type="integer", nullable=false)
     */
    private $invEmpaque;

    /**
     * @var string
     *
     * @ORM\Column(name="INV_OBSERVACION", type="string", length=45, nullable=true)
     */
    private $invObservacion;

    /**
     * @var \Parte
     *
     * @ORM\ManyToOne(targetEntity="Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PARTE_PAR_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $partePar;

    /**
     * @var \Proveedor
     *
     * @ORM\ManyToOne(targetEntity="Proveedor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PROVEEDOR_PROV_ID", referencedColumnName="PROV_ID")
     * })
     */
    private $proveedorProv;



    /**
     * Get invId
     *
     * @return integer
     */
    public function getInvId()
    {
        return $this->invId;
    }

    /**
     * Set invCantidad
     *
     * @param integer $invCantidad
     *
     * @return Inventario
     */
    public function setInvCantidad($invCantidad)
    {
        $this->invCantidad = $invCantidad;

        return $this;
    }

    /**
     * Get invCantidad
     *
     * @return integer
     */
    public function getInvCantidad()
    {
        return $this->invCantidad;
    }

    /**
     * Set invPrecio
     *
     * @param integer $invPrecio
     *
     * @return Inventario
     */
    public function setInvPrecio($invPrecio)
    {
        $this->invPrecio = $invPrecio;

        return $this;
    }

    /**
     * Get invPrecio
     *
     * @return integer
     */
    public function getInvPrecio()
    {
        return $this->invPrecio;
    }

    /**
     * Set invEmpaque
     *
     * @param integer $invEmpaque
     *
     * @return Inventario
     */
    public function setInvEmpaque($invEmpaque)
    {
        $this->invEmpaque = $invEmpaque;

        return $this;
    }

    /**
     * Get invEmpaque
     *
     * @return integer
     */
    public function getInvEmpaque()
    {
        return $this->invEmpaque;
    }

    /**
     * Set invObservacion
     *
     * @param string $invObservacion
     *
     * @return Inventario
     */
    public function setInvObservacion($invObservacion)
    {
        $this->invObservacion = $invObservacion;

        return $this;
    }

    /**
     * Get invObservacion
     *
     * @return string
     */
    public function getInvObservacion()
    {
        return $this->invObservacion;
    }

    /**
     * Set partePar
     *
     * @param \AppBundle\Entity\Parte $partePar
     *
     * @return Inventario
     */
    public function setPartePar(\AppBundle\Entity\Parte $partePar = null)
    {
        $this->partePar = $partePar;

        return $this;
    }

    /**
     * Get partePar
     *
     * @return \AppBundle\Entity\Parte
     */
    public function getPartePar()
    {
        return $this->partePar;
    }

    /**
     * Set proveedorProv
     *
     * @param \AppBundle\Entity\Proveedor $proveedorProv
     *
     * @return Inventario
     */
    public function setProveedorProv(\AppBundle\Entity\Proveedor $proveedorProv = null)
    {
        $this->proveedorProv = $proveedorProv;

        return $this;
    }

    /**
     * Get proveedorProv
     *
     * @return \AppBundle\Entity\Proveedor
     */
    public function getProveedorProv()
    {
        return $this->proveedorProv;
    }
}
