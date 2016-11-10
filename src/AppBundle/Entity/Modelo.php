<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Modelo
 *
 * @ORM\Table(name="modelo", uniqueConstraints={@ORM\UniqueConstraint(name="MAR_ID_UNIQUE", columns={"MOD_ID"})}, indexes={@ORM\Index(name="fk_MODELO_MARCA1_idx", columns={"MARCA_MAR_ID"})})
 * @ORM\Entity
 */
class Modelo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="MOD_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $modId;

    /**
     * @var string
     *
     * @ORM\Column(name="MOD_NOMBRE", type="string", length=45, nullable=false)
     */
    private $modNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="MOD_OBSERVACION", type="string", length=45, nullable=true)
     */
    private $modObservacion;

    /**
     * @var \AppBundle\Entity\Marca
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Marca")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="MARCA_MAR_ID", referencedColumnName="MAR_ID")
     * })
     */
    private $marcaMar;



    /**
     * Get modId
     *
     * @return integer
     */
    public function getModId()
    {
        return $this->modId;
    }

    /**
     * Set modNombre
     *
     * @param string $modNombre
     *
     * @return Modelo
     */
    public function setModNombre($modNombre)
    {
        $this->modNombre = $modNombre;

        return $this;
    }

    /**
     * Get modNombre
     *
     * @return string
     */
    public function getModNombre()
    {
        return $this->modNombre;
    }

    /**
     * Set modObservacion
     *
     * @param string $modObservacion
     *
     * @return Modelo
     */
    public function setModObservacion($modObservacion)
    {
        $this->modObservacion = $modObservacion;

        return $this;
    }

    /**
     * Get modObservacion
     *
     * @return string
     */
    public function getModObservacion()
    {
        return $this->modObservacion;
    }

    /**
     * Set marcaMar
     *
     * @param \AppBundle\Entity\Marca $marcaMar
     *
     * @return Modelo
     */
    public function setMarcaMar(\AppBundle\Entity\Marca $marcaMar = null)
    {
        $this->marcaMar = $marcaMar;

        return $this;
    }

    /**
     * Get marcaMar
     *
     * @return \AppBundle\Entity\Marca
     */
    public function getMarcaMar()
    {
        return $this->marcaMar;
    }
}
