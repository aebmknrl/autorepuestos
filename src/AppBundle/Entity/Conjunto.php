<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conjunto
 *
 * @ORM\Table(name="conjunto", indexes={@ORM\Index(name="fk_parte_id_idx", columns={"PARTE_PAR_ID"})})
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
     *   @ORM\JoinColumn(name="PARTE_PAR_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $partePar;



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
     * Set partePar
     *
     * @param \AppBundle\Entity\Parte $partePar
     *
     * @return Conjunto
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
