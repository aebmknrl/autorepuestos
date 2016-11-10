<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ConjuntoV2
 *
 * @ORM\Table(name="conjunto_v2")
 * @ORM\Entity
 */
class ConjuntoV2
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
     * @ORM\Column(name="kit_ref", type="string", length=15, nullable=false)
     */
    private $kitRef;

    /**
     * @var integer
     *
     * @ORM\Column(name="parte_id", type="integer", nullable=false)
     */
    private $parteId;

    /**
     * @var integer
     *
     * @ORM\Column(name="kit_count", type="integer", nullable=true)
     */
    private $kitCount;



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
     * Set kitRef
     *
     * @param string $kitRef
     *
     * @return ConjuntoV2
     */
    public function setKitRef($kitRef)
    {
        $this->kitRef = $kitRef;

        return $this;
    }

    /**
     * Get kitRef
     *
     * @return string
     */
    public function getKitRef()
    {
        return $this->kitRef;
    }

    /**
     * Set parteId
     *
     * @param integer $parteId
     *
     * @return ConjuntoV2
     */
    public function setParteId($parteId)
    {
        $this->parteId = $parteId;

        return $this;
    }

    /**
     * Get parteId
     *
     * @return integer
     */
    public function getParteId()
    {
        return $this->parteId;
    }

    /**
     * Set kitCount
     *
     * @param integer $kitCount
     *
     * @return ConjuntoV2
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
}
