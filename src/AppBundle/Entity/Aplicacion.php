<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aplicacion
 *
 * @ORM\Table(name="aplicacion", indexes={@ORM\Index(name="fk_APLICACION_VEHICULO1_idx", columns={"VEHICULO_VEH_ID"}), @ORM\Index(name="fk_APLICACION_PARTE1_idx", columns={"PARTE_PAR_ID"})})
 * @ORM\Entity
 */
class Aplicacion
{
    /**
     * @var integer
     *
     * @ORM\Column(name="APL_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $aplId;

    /**
     * @var integer
     *
     * @ORM\Column(name="APL_CANTIDAD", type="integer", nullable=false)
     */
    private $aplCantidad;

    /**
     * @var string
     *
     * @ORM\Column(name="APL_OBSERVACION", type="string", length=45, nullable=true)
     */
    private $aplObservacion;

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
     * @var \Vehiculo
     *
     * @ORM\ManyToOne(targetEntity="Vehiculo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="VEHICULO_VEH_ID", referencedColumnName="VEH_ID")
     * })
     */
    private $vehiculoVeh;



    /**
     * Get aplId
     *
     * @return integer
     */
    public function getAplId()
    {
        return $this->aplId;
    }

    /**
     * Set aplCantidad
     *
     * @param integer $aplCantidad
     *
     * @return Aplicacion
     */
    public function setAplCantidad($aplCantidad)
    {
        $this->aplCantidad = $aplCantidad;

        return $this;
    }

    /**
     * Get aplCantidad
     *
     * @return integer
     */
    public function getAplCantidad()
    {
        return $this->aplCantidad;
    }

    /**
     * Set aplObservacion
     *
     * @param string $aplObservacion
     *
     * @return Aplicacion
     */
    public function setAplObservacion($aplObservacion)
    {
        $this->aplObservacion = $aplObservacion;

        return $this;
    }

    /**
     * Get aplObservacion
     *
     * @return string
     */
    public function getAplObservacion()
    {
        return $this->aplObservacion;
    }

    /**
     * Set partePar
     *
     * @param \AppBundle\Entity\Parte $partePar
     *
     * @return Aplicacion
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
     * Set vehiculoVeh
     *
     * @param \AppBundle\Entity\Vehiculo $vehiculoVeh
     *
     * @return Aplicacion
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
