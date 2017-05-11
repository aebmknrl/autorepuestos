<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VehiculoNota
 *
 * @ORM\Table(name="vehiculo_nota", indexes={@ORM\Index(name="fk_vehiculo_nota_idx", columns={"VEHICULO_VEH_ID"})})
 * @ORM\Entity
 */
class VehiculoNota
{
    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_NOTA_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $vehNotaId;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_NOTA", type="string", length=100, nullable=true)
     */
    private $vehNota;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_NOTA", type="date", nullable=true)
     */
    private $fechaNota;

    /**
     * @var \Vehiculo
     *
     * @ORM\ManyToOne(targetEntity="Vehiculo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="VEHICULO_VEH_ID", referencedColumnName="VEH_ID")
     * })
     */
    private $vehiculoVeh;



    /**
     * Get vehNotaId
     *
     * @return integer
     */
    public function getVehNotaId()
    {
        return $this->vehNotaId;
    }

    /**
     * Set vehNota
     *
     * @param string $vehNota
     *
     * @return VehiculoNota
     */
    public function setVehNota($vehNota)
    {
        $this->vehNota = $vehNota;

        return $this;
    }

    /**
     * Get vehNota
     *
     * @return string
     */
    public function getVehNota()
    {
        return $this->vehNota;
    }

    /**
     * Set fechaNota
     *
     * @param \DateTime $fechaNota
     *
     * @return VehiculoNota
     */
    public function setFechaNota($fechaNota)
    {
        $this->fechaNota = $fechaNota;

        return $this;
    }

    /**
     * Get fechaNota
     *
     * @return \DateTime
     */
    public function getFechaNota()
    {
        return $this->fechaNota;
    }

    /**
     * Set vehiculoVeh
     *
     * @param \AppBundle\Entity\Vehiculo $vehiculoVeh
     *
     * @return VehiculoNota
     */
    public function setVehiculoVeh(\AppBundle\Entity\Vehiculo $vehiculoVeh = null)
    {
        $this->vehiculoVeh = $vehiculoVeh;

        return $this;
    }

    /**
     * Get vehiculoVeh
     *
     * @return \AppBundle\Entity\Vehiculo
     */
    public function getVehiculoVeh()
    {
        return $this->vehiculoVeh;
    }
}
