<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Grupo
 *
 * @ORM\Table(name="grupo", uniqueConstraints={@ORM\UniqueConstraint(name="grupo_nombre_UNIQUE", columns={"grupo_nombre"})}, indexes={@ORM\Index(name="fk_grupo_padre_idx", columns={"grupo_padre"})})
 * @ORM\Entity
 */
class Grupo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo_nombre", type="string", length=45, nullable=false)
     */
    private $grupoNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=240, nullable=true)
     */
    private $descripcion;

    /**
     * @var \AppBundle\Entity\Grupo
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Grupo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grupo_padre", referencedColumnName="id")
     * })
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
     * @param string $descripcion
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
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set grupoPadre
     *
     * @param \AppBundle\Entity\Grupo $grupoPadre
     *
     * @return Grupo
     */
    public function setGrupoPadre(\AppBundle\Entity\Grupo $grupoPadre = null)
    {
        $this->grupoPadre = $grupoPadre;

        return $this;
    }

    /**
     * Get grupoPadre
     *
     * @return \AppBundle\Entity\Grupo
     */
    public function getGrupoPadre()
    {
        return $this->grupoPadre;
    }
}
