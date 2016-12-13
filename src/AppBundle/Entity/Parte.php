<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parte
 *
 * @ORM\Table(name="parte", uniqueConstraints={@ORM\UniqueConstraint(name="PAR_ID_UNIQUE", columns={"PAR_ID"}), @ORM\UniqueConstraint(name="PAR_UPC_UNIQUE", columns={"PAR_UPC"})}, indexes={@ORM\Index(name="fk_PARTE_EQ2_idx", columns={"PAR_EQ_ID"}), @ORM\Index(name="fk_PARTE_FABRICANTE1_idx", columns={"FABRICANTE_FAB_ID"}), @ORM\Index(name="fk_PARTE_KIT2_idx", columns={"KIT_ID"})})
 * @ORM\Entity
 */
class Parte
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PAR_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $parId;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_UPC", type="string", length=45, nullable=true)
     */
    private $parUpc;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_NOMBRE", type="string", length=100, nullable=false)
     */
    private $parNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_NOMBRET", type="string", length=100, nullable=false)
     */
    private $parNombret;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_NOMBREIN", type="string", length=45, nullable=true)
     */
    private $parNombrein;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_ASIN", type="string", length=45, nullable=true)
     */
    private $parAsin;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_CODIGO", type="string", length=45, nullable=false)
     */
    private $parCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_GRUPO", type="string", length=45, nullable=true)
     */
    private $parGrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_SUBGRUPO", type="string", length=45, nullable=true)
     */
    private $parSubgrupo;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_LARGO", type="string", length=45, nullable=false)
     */
    private $parLargo;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_ANCHO", type="string", length=45, nullable=false)
     */
    private $parAncho;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_ESPESOR", type="string", length=45, nullable=true)
     */
    private $parEspesor;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_PESO", type="string", length=45, nullable=false)
     */
    private $parPeso;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_CARACT", type="string", length=200, nullable=true)
     */
    private $parCaract;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_OBSERVACION", type="string", length=200, nullable=true)
     */
    private $parObservacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="PAR_KIT", type="boolean", nullable=true)
     */
    private $parKit;

    /**
     * @var \AppBundle\Entity\Equivalencia
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equivalencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PAR_EQ_ID", referencedColumnName="ID")
     * })
     */
    private $parEq;

    /**
     * @var \AppBundle\Entity\Fabricante
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Fabricante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FABRICANTE_FAB_ID", referencedColumnName="FAB_ID")
     * })
     */
    private $fabricanteFab;

    /**
     * @var \AppBundle\Entity\Conjunto
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Conjunto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="KIT_ID", referencedColumnName="ID")
     * })
     */
    private $kit;



    /**
     * Get parId
     *
     * @return integer
     */
    public function getParId()
    {
        return $this->parId;
    }

    /**
     * Set parUpc
     *
     * @param string $parUpc
     *
     * @return Parte
     */
    public function setParUpc($parUpc)
    {
        $this->parUpc = $parUpc;

        return $this;
    }

    /**
     * Get parUpc
     *
     * @return string
     */
    public function getParUpc()
    {
        return $this->parUpc;
    }

    /**
     * Set parNombre
     *
     * @param string $parNombre
     *
     * @return Parte
     */
    public function setParNombre($parNombre)
    {
        $this->parNombre = $parNombre;

        return $this;
    }

    /**
     * Get parNombre
     *
     * @return string
     */
    public function getParNombre()
    {
        return $this->parNombre;
    }

    /**
     * Set parNombret
     *
     * @param string $parNombret
     *
     * @return Parte
     */
    public function setParNombret($parNombret)
    {
        $this->parNombret = $parNombret;

        return $this;
    }

    /**
     * Get parNombret
     *
     * @return string
     */
    public function getParNombret()
    {
        return $this->parNombret;
    }

    /**
     * Set parNombrein
     *
     * @param string $parNombrein
     *
     * @return Parte
     */
    public function setParNombrein($parNombrein)
    {
        $this->parNombrein = $parNombrein;

        return $this;
    }

    /**
     * Get parNombrein
     *
     * @return string
     */
    public function getParNombrein()
    {
        return $this->parNombrein;
    }

    /**
     * Set parAsin
     *
     * @param string $parAsin
     *
     * @return Parte
     */
    public function setParAsin($parAsin)
    {
        $this->parAsin = $parAsin;

        return $this;
    }

    /**
     * Get parAsin
     *
     * @return string
     */
    public function getParAsin()
    {
        return $this->parAsin;
    }

    /**
     * Set parCodigo
     *
     * @param string $parCodigo
     *
     * @return Parte
     */
    public function setParCodigo($parCodigo)
    {
        $this->parCodigo = $parCodigo;

        return $this;
    }

    /**
     * Get parCodigo
     *
     * @return string
     */
    public function getParCodigo()
    {
        return $this->parCodigo;
    }

    /**
     * Set parGrupo
     *
     * @param string $parGrupo
     *
     * @return Parte
     */
    public function setParGrupo($parGrupo)
    {
        $this->parGrupo = $parGrupo;

        return $this;
    }

    /**
     * Get parGrupo
     *
     * @return string
     */
    public function getParGrupo()
    {
        return $this->parGrupo;
    }

    /**
     * Set parSubgrupo
     *
     * @param string $parSubgrupo
     *
     * @return Parte
     */
    public function setParSubgrupo($parSubgrupo)
    {
        $this->parSubgrupo = $parSubgrupo;

        return $this;
    }

    /**
     * Get parSubgrupo
     *
     * @return string
     */
    public function getParSubgrupo()
    {
        return $this->parSubgrupo;
    }

    /**
     * Set parLargo
     *
     * @param string $parLargo
     *
     * @return Parte
     */
    public function setParLargo($parLargo)
    {
        $this->parLargo = $parLargo;

        return $this;
    }

    /**
     * Get parLargo
     *
     * @return string
     */
    public function getParLargo()
    {
        return $this->parLargo;
    }

    /**
     * Set parAncho
     *
     * @param string $parAncho
     *
     * @return Parte
     */
    public function setParAncho($parAncho)
    {
        $this->parAncho = $parAncho;

        return $this;
    }

    /**
     * Get parAncho
     *
     * @return string
     */
    public function getParAncho()
    {
        return $this->parAncho;
    }

    /**
     * Set parEspesor
     *
     * @param string $parEspesor
     *
     * @return Parte
     */
    public function setParEspesor($parEspesor)
    {
        $this->parEspesor = $parEspesor;

        return $this;
    }

    /**
     * Get parEspesor
     *
     * @return string
     */
    public function getParEspesor()
    {
        return $this->parEspesor;
    }

    /**
     * Set parPeso
     *
     * @param string $parPeso
     *
     * @return Parte
     */
    public function setParPeso($parPeso)
    {
        $this->parPeso = $parPeso;

        return $this;
    }

    /**
     * Get parPeso
     *
     * @return string
     */
    public function getParPeso()
    {
        return $this->parPeso;
    }

    /**
     * Set parCaract
     *
     * @param string $parCaract
     *
     * @return Parte
     */
    public function setParCaract($parCaract)
    {
        $this->parCaract = $parCaract;

        return $this;
    }

    /**
     * Get parCaract
     *
     * @return string
     */
    public function getParCaract()
    {
        return $this->parCaract;
    }

    /**
     * Set parObservacion
     *
     * @param string $parObservacion
     *
     * @return Parte
     */
    public function setParObservacion($parObservacion)
    {
        $this->parObservacion = $parObservacion;

        return $this;
    }

    /**
     * Get parObservacion
     *
     * @return string
     */
    public function getParObservacion()
    {
        return $this->parObservacion;
    }

    /**
     * Set parKit
     *
     * @param boolean $parKit
     *
     * @return Parte
     */
    public function setParKit($parKit)
    {
        $this->parKit = $parKit;

        return $this;
    }

    /**
     * Get parKit
     *
     * @return boolean
     */
    public function getParKit()
    {
        return $this->parKit;
    }

    /**
     * Set parEq
     *
     * @param \AppBundle\Entity\Equivalencia $parEq
     *
     * @return Parte
     */
    public function setParEq(\AppBundle\Entity\Equivalencia $parEq = null)
    {
        $this->parEq = $parEq;

        return $this;
    }

    /**
     * Get parEq
     *
     * @return \AppBundle\Entity\Equivalencia
     */
    public function getParEq()
    {
        return $this->parEq;
    }

    /**
     * Set fabricanteFab
     *
     * @param \AppBundle\Entity\Fabricante $fabricanteFab
     *
     * @return Parte
     */
    public function setFabricanteFab(\AppBundle\Entity\Fabricante $fabricanteFab = null)
    {
        $this->fabricanteFab = $fabricanteFab;

        return $this;
    }

    /**
     * Get fabricanteFab
     *
     * @return \AppBundle\Entity\Fabricante
     */
    public function getFabricanteFab()
    {
        return $this->fabricanteFab;
    }

    /**
     * Set kit
     *
     * @param \AppBundle\Entity\Conjunto $kit
     *
     * @return Parte
     */
    public function setKit(\AppBundle\Entity\Conjunto $kit = null)
    {
        $this->kit = $kit;

        return $this;
    }

    /**
     * Get kit
     *
     * @return \AppBundle\Entity\Conjunto
     */
    public function getKit()
    {
        return $this->kit;
    }
}
