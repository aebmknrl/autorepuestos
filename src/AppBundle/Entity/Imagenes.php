<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagenes
 *
 * @ORM\Table(name="imagenes", indexes={@ORM\Index(name="fk_IMAGENES_PARTE1_idx", columns={"PARTE_PAR_ID"})})
 * @ORM\Entity
 */
class Imagenes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IMG_ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $imgId;

    /**
     * @var string
     *
     * @ORM\Column(name="IMG_UBICACION", type="string", length=45, nullable=true)
     */
    private $imgUbicacion;

    /**
     * @var \AppBundle\Entity\Parte
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PARTE_PAR_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $partePar;



    /**
     * Get imgId
     *
     * @return integer
     */
    public function getImgId()
    {
        return $this->imgId;
    }

    /**
     * Set imgUbicacion
     *
     * @param string $imgUbicacion
     *
     * @return Imagenes
     */
    public function setImgUbicacion($imgUbicacion)
    {
        $this->imgUbicacion = $imgUbicacion;

        return $this;
    }

    /**
     * Get imgUbicacion
     *
     * @return string
     */
    public function getImgUbicacion()
    {
        return $this->imgUbicacion;
    }

    /**
     * Set partePar
     *
     * @param \AppBundle\Entity\Parte $partePar
     *
     * @return Imagenes
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
}
