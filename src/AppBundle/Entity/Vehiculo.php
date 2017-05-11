<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vehiculo
 *
 * @ORM\Table(name="vehiculo", uniqueConstraints={@ORM\UniqueConstraint(name="VEH_ID_UNIQUE", columns={"VEH_ID"})}, indexes={@ORM\Index(name="fk_VEHICULO_MODELO1_idx", columns={"MODELO_MOD_ID"})})
 * @ORM\Entity
 */
class Vehiculo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_ID", type="integer", nullable=false)
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
     * @ORM\Column(name="VEH_VIN", type="string", length=45, nullable=true)
     */
    private $vehVin;

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
     * @var string
     *
     * @ORM\Column(name="VEH_VARIANTE", type="string", length=45, nullable=true)
     */
    private $vehVariante;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_CILINDROS", type="string", length=10, nullable=true)
     */
    private $vehCilindros;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_LITROS", type="string", length=10, nullable=true)
     */
    private $vehLitros;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_VALVULAS", type="string", length=10, nullable=true)
     */
    private $vehValvulas;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_LEVAS", type="string", length=10, nullable=true)
     */
    private $vehLevas;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_VERSION", type="string", length=20, nullable=true)
     */
    private $vehVersion;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_TIPO", type="string", length=20, nullable=true)
     */
    private $vehTipo;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_TRACCION", type="string", length=20, nullable=true)
     */
    private $vehTraccion;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_CAJA", type="string", length=20, nullable=true)
     */
    private $vehCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_OBSERVACION", type="string", length=20, nullable=true)
     */
    private $vehObservacion;

    /**
     * @var string
     *
     * @ORM\Column(name="NOTA", type="string", length=250, nullable=true)
     */
    private $nota;

    /**
     * @var \Modelo
     *
     * @ORM\ManyToOne(targetEntity="Modelo")
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
     * Set vehCilindros
     *
     * @param string $vehCilindros
     *
     * @return Vehiculo
     */
    public function setVehCilindros($vehCilindros)
    {
        $this->vehCilindros = $vehCilindros;

        return $this;
    }

    /**
     * Get vehCilindros
     *
     * @return string
     */
    public function getVehCilindros()
    {
        return $this->vehCilindros;
    }

    /**
     * Set vehLitros
     *
     * @param string $vehLitros
     *
     * @return Vehiculo
     */
    public function setVehLitros($vehLitros)
    {
        $this->vehLitros = $vehLitros;

        return $this;
    }

    /**
     * Get vehLitros
     *
     * @return string
     */
    public function getVehLitros()
    {
        return $this->vehLitros;
    }

    /**
     * Set vehValvulas
     *
     * @param string $vehValvulas
     *
     * @return Vehiculo
     */
    public function setVehValvulas($vehValvulas)
    {
        $this->vehValvulas = $vehValvulas;

        return $this;
    }

    /**
     * Get vehValvulas
     *
     * @return string
     */
    public function getVehValvulas()
    {
        return $this->vehValvulas;
    }

    /**
     * Set vehLevas
     *
     * @param string $vehLevas
     *
     * @return Vehiculo
     */
    public function setVehLevas($vehLevas)
    {
        $this->vehLevas = $vehLevas;

        return $this;
    }

    /**
     * Get vehLevas
     *
     * @return string
     */
    public function getVehLevas()
    {
        return $this->vehLevas;
    }

    /**
     * Set vehVersion
     *
     * @param string $vehVersion
     *
     * @return Vehiculo
     */
    public function setVehVersion($vehVersion)
    {
        $this->vehVersion = $vehVersion;

        return $this;
    }

    /**
     * Get vehVersion
     *
     * @return string
     */
    public function getVehVersion()
    {
        return $this->vehVersion;
    }

    /**
     * Set vehTipo
     *
     * @param string $vehTipo
     *
     * @return Vehiculo
     */
    public function setVehTipo($vehTipo)
    {
        $this->vehTipo = $vehTipo;

        return $this;
    }

    /**
     * Get vehTipo
     *
     * @return string
     */
    public function getVehTipo()
    {
        return $this->vehTipo;
    }

    /**
     * Set vehTraccion
     *
     * @param string $vehTraccion
     *
     * @return Vehiculo
     */
    public function setVehTraccion($vehTraccion)
    {
        $this->vehTraccion = $vehTraccion;

        return $this;
    }

    /**
     * Get vehTraccion
     *
     * @return string
     */
    public function getVehTraccion()
    {
        return $this->vehTraccion;
    }

    /**
     * Set vehCaja
     *
     * @param string $vehCaja
     *
     * @return Vehiculo
     */
    public function setVehCaja($vehCaja)
    {
        $this->vehCaja = $vehCaja;

        return $this;
    }

    /**
     * Get vehCaja
     *
     * @return string
     */
    public function getVehCaja()
    {
        return $this->vehCaja;
    }

    /**
     * Set vehObservacion
     *
     * @param string $vehObservacion
     *
     * @return Vehiculo
     */
    public function setVehObservacion($vehObservacion)
    {
        $this->vehObservacion = $vehObservacion;

        return $this;
    }

    /**
     * Get vehObservacion
     *
     * @return string
     */
    public function getVehObservacion()
    {
        return $this->vehObservacion;
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
