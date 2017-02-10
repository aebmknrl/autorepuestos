<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombreParte
 *
 * @ORM\Table(name="nombre_parte")
 * @ORM\Entity
 */
class NombreParte
{
    /**
     * @var integer
     *
     * @ORM\Column(name="PAR_NOMBRE_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $parNombreId;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_NOMBRE", type="string", length=100, nullable=true)
     */
    private $parNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_NOMBRE_INGLES", type="string", length=100, nullable=true)
     */
    private $parNombreIngles;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_NOMBRE_OTROS", type="string", length=250, nullable=true)
     */
    private $parNombreOtros;

    /**
     * @var string
     *
     * @ORM\Column(name="PAR_GRUPO_ID", type="string", length=45, nullable=true)
     */
    private $parGrupoId;



    /**
     * Get parNombreId
     *
     * @return integer
     */
    public function getParNombreId()
    {
        return $this->parNombreId;
    }

    /**
     * Set parNombre
     *
     * @param string $parNombre
     *
     * @return NombreParte
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
     * Set parNombreIngles
     *
     * @param string $parNombreIngles
     *
     * @return NombreParte
     */
    public function setParNombreIngles($parNombreIngles)
    {
        $this->parNombreIngles = $parNombreIngles;

        return $this;
    }

    /**
     * Get parNombreIngles
     *
     * @return string
     */
    public function getParNombreIngles()
    {
        return $this->parNombreIngles;
    }

    /**
     * Set parNombreOtros
     *
     * @param string $parNombreOtros
     *
     * @return NombreParte
     */
    public function setParNombreOtros($parNombreOtros)
    {
        $this->parNombreOtros = $parNombreOtros;

        return $this;
    }

    /**
     * Get parNombreOtros
     *
     * @return string
     */
    public function getParNombreOtros()
    {
        return $this->parNombreOtros;
    }

    /**
     * Set parGrupoId
     *
     * @param string $parGrupoId
     *
     * @return NombreParte
     */
    public function setParGrupoId($parGrupoId)
    {
        $this->parGrupoId = $parGrupoId;

        return $this;
    }

    /**
     * Get parGrupoId
     *
     * @return string
     */
    public function getParGrupoId()
    {
        return $this->parGrupoId;
    }
}
