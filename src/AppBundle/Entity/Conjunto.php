<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conjunto
 *
 * @ORM\Table(name="conjunto", indexes={@ORM\Index(name="fk_parte_id_idx", columns={"PARTE_ID"}), @ORM\Index(name="fk_partekit_id_idx", columns={"PARTEKIT_ID"})})
 * @ORM\Entity
 */
class Conjunto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="KIT_REF", type="string", length=15, nullable=false)
     */
    private $kitRef;

    /**
     * @var integer
     *
     * @ORM\Column(name="KIT_COUNT", type="integer", nullable=true)
     */
    private $kitCount;

    /**
     * @var \AppBundle\Entity\Parte
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PARTE_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $parte;

    /**
     * @var \AppBundle\Entity\Parte
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PARTEKIT_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $partekit;



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
     * @return Conjunto
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

    /**
     * Set partekit
     *
     * @param \AppBundle\Entity\Parte $partekit
     *
     * @return Conjunto
     */
    public function setPartekit(\AppBundle\Entity\Parte $partekit = null)
    {
        $this->partekit = $partekit;

        return $this;
    }

    /**
     * Get partekit
     *
     * @return \AppBundle\Entity\Parte
     */
    public function getPartekit()
    {
        return $this->partekit;
    }
}
