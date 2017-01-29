<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imagen
 *
 * @ORM\Table(name="imagen", uniqueConstraints={@ORM\UniqueConstraint(name="IMAGEN_FILE_UNIQUE", columns={"IMG_FILE"})}, indexes={@ORM\Index(name="fk_IMAGENES_PARTE1_idx", columns={"PARTE_PAR_ID"})})
 * @ORM\Entity
 */
class Imagen
{
    /**
     * @var integer
     *
     * @ORM\Column(name="IMG_ID", type="integer", nullable=false)
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
     * @var string
     *
     * @ORM\Column(name="IMG_FILE", type="string", length=100, nullable=false)
     */
    private $imgFile;

    /**
     * @var \Parte
     *
     * @ORM\ManyToOne(targetEntity="Parte")
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
     * @return Imagen
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
     * Set imgFile
     *
     * @param string $imgFile
     *
     * @return Imagen
     */
    public function setImgFile($imgFile)
    {
        $this->imgFile = $imgFile;

        return $this;
    }

    /**
     * Get imgFile
     *
     * @return string
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }

    /**
     * Set partePar
     *
     * @param \AppBundle\Entity\Parte $partePar
     *
     * @return Imagen
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
