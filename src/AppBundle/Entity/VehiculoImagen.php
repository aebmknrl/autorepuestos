<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VehiculoImagen
 *
 * @ORM\Table(name="vehiculo_imagen", uniqueConstraints={@ORM\UniqueConstraint(name="index3", columns={"VEH_IMG_NOMBRE", "VEH_ID"})}, indexes={@ORM\Index(name="fk_veh_id_idx", columns={"VEH_ID"})})
 * @ORM\Entity
 */
class VehiculoImagen
{
    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_IMG_ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $vehImgId;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_IMG_NOMBRE", type="string", length=45, nullable=false)
     */
    private $vehImgNombre;

    /**
     * @var string
     *
     * @ORM\Column(name="VEH_IMG_URL", type="string", length=45, nullable=true)
     */
    private $vehImgUrl;

    /**
     * @var integer
     *
     * @ORM\Column(name="VEH_ID", type="integer", nullable=false)
     */
    private $vehId;



    /**
     * Get vehImgId
     *
     * @return integer
     */
    public function getVehImgId()
    {
        return $this->vehImgId;
    }

    /**
     * Set vehImgNombre
     *
     * @param string $vehImgNombre
     *
     * @return VehiculoImagen
     */
    public function setVehImgNombre($vehImgNombre)
    {
        $this->vehImgNombre = $vehImgNombre;

        return $this;
    }

    /**
     * Get vehImgNombre
     *
     * @return string
     */
    public function getVehImgNombre()
    {
        return $this->vehImgNombre;
    }

    /**
     * Set vehImgUrl
     *
     * @param string $vehImgUrl
     *
     * @return VehiculoImagen
     */
    public function setVehImgUrl($vehImgUrl)
    {
        $this->vehImgUrl = $vehImgUrl;

        return $this;
    }

    /**
     * Get vehImgUrl
     *
     * @return string
     */
    public function getVehImgUrl()
    {
        return $this->vehImgUrl;
    }

    /**
     * Set vehId
     *
     * @param integer $vehId
     *
     * @return VehiculoImagen
     */
    public function setVehId($vehId)
    {
        $this->vehId = $vehId;

        return $this;
    }

    /**
     * Get vehId
     *
     * @return integer
     */
    public function getVehId()
    {
        return $this->vehId;
    }
}
