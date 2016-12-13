<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehiculo
 *
 * @ORM\Table(name="vehiculo", uniqueConstraints={@ORM\UniqueConstraint(name="VEH_ID_UNIQUE", columns={"VEH_ID"}), @ORM\UniqueConstraint(name="inx_var_mod", columns={"MODELO_MOD_ID", "VEH_VARIANTE"})}, indexes={@ORM\Index(name="fk_VEHICULO_MODELO1_idx", columns={"MODELO_MOD_ID"})})
 * @ORM\Entity
 */
class Vehiculo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $vehId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ANIO_ANI_ID", type="integer", nullable=false)
     */
    private $anioAniId;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_VARIANTE", type="string", length=45, nullable=false)
     */
    private $vehVariante;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_VIN", type="string", length=45, nullable=true)
     */
    private $vehVin;

    /**
     * @var string
     *
     * @ORM\Column(name="NOTA", type="string", length=250, nullable=true)
     */
    private $nota;

    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_FAB_DESDE", type="integer", nullable=true)
     */
    private $vehFabDesde;

    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_FAB_HASTA", type="integer", nullable=true)
     */
    private $vehFabHasta;

    /**
     * @var \AppBundle\Entity\Modelo
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Modelo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MODELO_MOD_ID", referencedColumnName="MOD_ID")
     * })
     */
    private $modeloMod;



    /**
     * Get vehId
     *
     * @return integer
     */
    public function getVehId()
    {
        return $this->vehId;
    }

    /**
     * Set anioAniId
     *
     * @param integer $anioAniId
     *
     * @return Vehiculo
     */
    public function setAnioAniId($anioAniId)
    {
        $this->anioAniId = $anioAniId;

        return $this;
    }

    /**
     * Get anioAniId
     *
     * @return integer
     */
    public function getAnioAniId()
    {
        return $this->anioAniId;
    }

    /**
     * Set vehVariante
     *
     * @param string $vehVariante
     *
     * @return Vehiculo
     */
    public function setVehVariante($vehVariante)
    {
        $this->vehVariante = $vehVariante;

        return $this;
    }

    /**
     * Get vehVariante
     *
     * @return string
     */
    public function getVehVariante()
    {
        return $this->vehVariante;
    }

    /**
     * Set vehVin
     *
     * @param string $vehVin
     *
     * @return Vehiculo
     */
    public function setVehVin($vehVin)
    {
        $this->vehVin = $vehVin;

        return $this;
    }

    /**
     * Get vehVin
     *
     * @return string
     */
    public function getVehVin()
    {
        return $this->vehVin;
    }

    /**
     * Set nota
     *
     * @param string $nota
     *
     * @return Vehiculo
     */
    public function setNota($nota)
    {
        $this->nota = $nota;

        return $this;
    }

    /**
     * Get nota
     *
     * @return string
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * Set vehFabDesde
     *
     * @param integer $vehFabDesde
     *
     * @return Vehiculo
     */
    public function setVehFabDesde($vehFabDesde)
    {
        $this->vehFabDesde = $vehFabDesde;

        return $this;
    }

    /**
     * Get vehFabDesde
     *
     * @return integer
     */
    public function getVehFabDesde()
    {
        return $this->vehFabDesde;
    }

    /**
     * Set vehFabHasta
     *
     * @param integer $vehFabHasta
     *
     * @return Vehiculo
     */
    public function setVehFabHasta($vehFabHasta)
    {
        $this->vehFabHasta = $vehFabHasta;

        return $this;
    }

    /**
     * Get vehFabHasta
     *
     * @return integer
     */
    public function getVehFabHasta()
    {
        return $this->vehFabHasta;
    }

    /**
     * Set modeloMod
     *
     * @param \AppBundle\Entity\Modelo $modeloMod
     *
     * @return Vehiculo
     */
    public function setModeloMod(\AppBundle\Entity\Modelo $modeloMod = null)
    {
        $this->modeloMod = $modeloMod;

        return $this;
    }

    /**
     * Get modeloMod
     *
     * @return \AppBundle\Entity\Modelo
     */
    public function getModeloMod()
    {
        return $this->modeloMod;
    }
}
