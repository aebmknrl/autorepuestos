<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Parte
 *
 * @ORM\Table(name="parte", uniqueConstraints={@ORM\UniqueConstraint(name="PAR_ID_UNIQUE", columns={"PAR_ID"}), @ORM\UniqueConstraint(name="PAR_UPC_UNIQUE", columns={"PAR_UPC"})}, indexes={@ORM\Index(name="fk_PARTE_FABRICANTE1_idx", columns={"FABRICANTE_FAB_ID"}), @ORM\Index(name="fk_PARTE_NOMBRE_idx", columns={"PAR_NOMBRE_ID"}), @ORM\Index(name="fk_PARTE_EQ_idx", columns={"PAR_EQ_ID"})})
 * @ORM\Entity
 */
class Parte
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PAR_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $parId;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_CODIGO", type="string", length=45, nullable=false)
     */
    private $parCodigo;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_UPC", type="string", length=45, nullable=true)
     */
    private $parUpc;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_SKU", type="string", length=20, nullable=true)
     */
    private $parSku;

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
     * @ORM\Column(name="PARTE_ORIGEN", type="string", length=30, nullable=true)
     */
    private $parteOrigen;

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
     * @var string
     *
     * @ORM\Column(name="PAR_ASIN", type="string", length=45, nullable=true)
     */
    private $parAsin;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_SUBGRUPO", type="string", length=45, nullable=true)
     */
    private $parSubgrupo;

    /**
     * @var integer
     *
     * @ORM\Column(name="PAR_KIT", type="integer", nullable=true)
     */
    private $parKit;

    /**
     * @var \Equivalencia
     *
     * @ORM\ManyToOne(targetEntity="Equivalencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PAR_EQ_ID", referencedColumnName="ID")
     * })
     */
    private $parEq;

    /**
     * @var \Fabricante
     *
     * @ORM\ManyToOne(targetEntity="Fabricante")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="FABRICANTE_FAB_ID", referencedColumnName="FAB_ID")
     * })
     */
    private $fabricanteFab;

    /**
     * @var \NombreParte
     *
     * @ORM\ManyToOne(targetEntity="NombreParte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PAR_NOMBRE_ID", referencedColumnName="PAR_NOMBRE_ID")
     * })
     */
    private $parNombre;



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
     * Set parSku
     *
     * @param string $parSku
     *
     * @return Parte
     */
    public function setParSku($parSku)
    {
        $this->parSku = $parSku;

        return $this;
    }

    /**
     * Get parSku
     *
     * @return string
     */
    public function getParSku()
    {
        return $this->parSku;
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
     * Set parteOrigen
     *
     * @param string $parteOrigen
     *
     * @return Parte
     */
    public function setParteOrigen($parteOrigen)
    {
        $this->parteOrigen = $parteOrigen;

        return $this;
    }

    /**
     * Get parteOrigen
     *
     * @return string
     */
    public function getParteOrigen()
    {
        return $this->parteOrigen;
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
     * Set parKit
     *
     * @param integer $parKit
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
     * @return integer
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
     * Set parNombre
     *
     * @param \AppBundle\Entity\NombreParte $parNombre
     *
     * @return Parte
     */
    public function setParNombre(\AppBundle\Entity\NombreParte $parNombre = null)
    {
        $this->parNombre = $parNombre;

        return $this;
    }

    /**
     * Get parNombre
     *
     * @return \AppBundle\Entity\NombreParte
     */
    public function getParNombre()
    {
        return $this->parNombre;
    }
}
