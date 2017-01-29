<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricoInv
 *
 * @ORM\Table(name="historico_inv", uniqueConstraints={@ORM\UniqueConstraint(name="HIS_INV_ID_UNIQUE", columns={"HIS_INV_ID"})}, indexes={@ORM\Index(name="fk_HISTORICO_INV_INVENTARIO1_idx", columns={"INVENTARIO_INV_ID"})})
 * @ORM\Entity
 */
class HistoricoInv
{
    /**
     * @var integer
     *
     * @ORM\Column(name="HIS_INV_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $hisInvId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="HIS_INV_FECHA", type="date", nullable=false)
     */
    private $hisInvFecha;

    /**
     * @var integer
     *
     * @ORM\Column(name="HIS_INV_CANTIDAD", type="integer", nullable=false)
     */
    private $hisInvCantidad;

    /**
     * @var \Inventario
     *
     * @ORM\ManyToOne(targetEntity="Inventario")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="INVENTARIO_INV_ID", referencedColumnName="INV_ID")
     * })
     */
    private $inventarioInv;



    /**
     * Get hisInvId
     *
     * @return integer
     */
    public function getHisInvId()
    {
        return $this->hisInvId;
    }

    /**
     * Set hisInvFecha
     *
     * @param \DateTime $hisInvFecha
     *
     * @return HistoricoInv
     */
    public function setHisInvFecha($hisInvFecha)
    {
        $this->hisInvFecha = $hisInvFecha;

        return $this;
    }

    /**
     * Get hisInvFecha
     *
     * @return \DateTime
     */
    public function getHisInvFecha()
    {
        return $this->hisInvFecha;
    }

    /**
     * Set hisInvCantidad
     *
     * @param integer $hisInvCantidad
     *
     * @return HistoricoInv
     */
    public function setHisInvCantidad($hisInvCantidad)
    {
        $this->hisInvCantidad = $hisInvCantidad;

        return $this;
    }

    /**
     * Get hisInvCantidad
     *
     * @return integer
     */
    public function getHisInvCantidad()
    {
        return $this->hisInvCantidad;
    }

    /**
     * Set inventarioInv
     *
     * @param \AppBundle\Entity\Inventario $inventarioInv
     *
     * @return HistoricoInv
     */
    public function setInventarioInv(\AppBundle\Entity\Inventario $inventarioInv = null)
    {
        $this->inventarioInv = $inventarioInv;

        return $this;
    }

    /**
     * Get inventarioInv
     *
     * @return \AppBundle\Entity\Inventario
     */
    public function getInventarioInv()
    {
        return $this->inventarioInv;
    }
}
