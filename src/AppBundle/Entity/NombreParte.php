<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombreParte
 *
 * @ORM\Table(name="nombre_parte", uniqueConstraints={@ORM\UniqueConstraint(name="nombre_grupo_unique", columns={"PAR_NOMBRE", "PAR_GRUPO_ID"})}, indexes={@ORM\Index(name="fk_grupo_idx", columns={"PAR_GRUPO_ID"})})
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
     * @ORM\Column(name="PAR_NOMBRE", type="string", length=100, nullable=false)
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
     * @var \Grupo
     *
     * @ORM\ManyToOne(targetEntity="Grupo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PAR_GRUPO_ID", referencedColumnName="id")
     * })
     */
    private $parGrupo;



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
     * Set parGrupo
     *
     * @param \AppBundle\Entity\Grupo $parGrupo
     *
     * @return NombreParte
     */
    public function setParGrupo(\AppBundle\Entity\Grupo $parGrupo = null)
    {
        $this->parGrupo = $parGrupo;

        return $this;
    }

    /**
     * Get parGrupo
     *
     * @return \AppBundle\Entity\Grupo
     */
    public function getParGrupo()
    {
        return $this->parGrupo;
    }
}
