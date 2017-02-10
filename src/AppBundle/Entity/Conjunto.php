<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conjunto
 *
 * @ORM\Table(name="conjunto", indexes={@ORM\Index(name="fk_parte_id_idx", columns={"PARTE_ID"})})
 * @ORM\Entity
 */
class Conjunto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="PARTE_KIT_ID", type="integer", nullable=true)
     */
    private $parteKitId;

    /**
     * @var integer
     *
     * @ORM\Column(name="KIT_COUNT", type="integer", nullable=true)
     */
    private $kitCount;

    /**
     * @var \Parte
     *
     * @ORM\ManyToOne(targetEntity="Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PARTE_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $parte;



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
     * Set parteKitId
     *
     * @param integer $parteKitId
     *
     * @return Conjunto
     */
    public function setParteKitId($parteKitId)
    {
        $this->parteKitId = $parteKitId;

        return $this;
    }

    /**
     * Get parteKitId
     *
     * @return integer
     */
    public function getParteKitId()
    {
        return $this->parteKitId;
    }

    /**
     * Set kitCount
     *
     * @param integer $kitCount
     *
     * @return Conjunto
     */
    public function setKitCount($kitCount)
    {
        $this->kitCount = $kitCount;

        return $this;
    }

    /**
     * Get kitCount
     *
     * @return integer
     */
    public function getKitCount()
    {
        return $this->kitCount;
    }

    /**
     * Set parte
     *
     * @param \AppBundle\Entity\Parte $parte
     *
     * @return Conjunto
     */
    public function setParte(\AppBundle\Entity\Parte $parte = null)
    {
        $this->parte = $parte;

        return $this;
    }

    /**
     * Get parte
     *
     * @return \AppBundle\Entity\Parte
     */
    public function getParte()
    {
        return $this->parte;
    }
}
