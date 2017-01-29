<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 *
 * @ORM\Table(name="grupo", indexes={@ORM\Index(name="fk_GRUPO_PADRE_idx", columns={"GRUPO_PADRE"})})
 * @ORM\Entity
 */
class Grupo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo_nombre", type="string", length=30, nullable=false)
     */
    private $grupoNombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="descripcion", type="integer", nullable=true)
     */
    private $descripcion;

    /**
     * @var integer
     *
     * @ORM\Column(name="grupo_padre", type="integer", nullable=true)
     */
    private $grupoPadre;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set grupoNombre
     *
     * @param string $grupoNombre
     *
     * @return Grupo
     */
    public function setGrupoNombre($grupoNombre)
    {
        $this->grupoNombre = $grupoNombre;

        return $this;
    }

    /**
     * Get grupoNombre
     *
     * @return string
     */
    public function getGrupoNombre()
    {
        return $this->grupoNombre;
    }

    /**
     * Set descripcion
     *
     * @param integer $descripcion
     *
     * @return Grupo
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return integer
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set grupoPadre
     *
     * @param integer $grupoPadre
     *
     * @return Grupo
     */
    public function setGrupoPadre($grupoPadre)
    {
        $this->grupoPadre = $grupoPadre;

        return $this;
    }

    /**
     * Get grupoPadre
     *
     * @return integer
     */
    public function getGrupoPadre()
    {
        return $this->grupoPadre;
    }
}
