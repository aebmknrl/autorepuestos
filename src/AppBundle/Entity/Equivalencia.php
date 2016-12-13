<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equivalencia
 *
 * @ORM\Table(name="equivalencia", indexes={@ORM\Index(name="fk_parte_eq1_idx", columns={"PART1_ID"}), @ORM\Index(name="fk_parte_eq2_idx", columns={"PART2_ID"})})
 * @ORM\Entity
 */
class Equivalencia
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
     * @ORM\Column(name="EQUIVALENCIA_REF", type="string", length=15, nullable=false)
     */
    private $equivalenciaRef;

    /**
     * @var \AppBundle\Entity\Parte
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PART1_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $part1;

    /**
     * @var \AppBundle\Entity\Parte
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Parte")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PART2_ID", referencedColumnName="PAR_ID")
     * })
     */
    private $part2;



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
     * Set equivalenciaRef
     *
     * @param string $equivalenciaRef
     *
     * @return Equivalencia
     */
    public function setEquivalenciaRef($equivalenciaRef)
    {
        $this->equivalenciaRef = $equivalenciaRef;

        return $this;
    }

    /**
     * Get equivalenciaRef
     *
     * @return string
     */
    public function getEquivalenciaRef()
    {
        return $this->equivalenciaRef;
    }

    /**
     * Set part1
     *
     * @param \AppBundle\Entity\Parte $part1
     *
     * @return Equivalencia
     */
    public function setPart1(\AppBundle\Entity\Parte $part1 = null)
    {
        $this->part1 = $part1;

        return $this;
    }

    /**
     * Get part1
     *
     * @return \AppBundle\Entity\Parte
     */
    public function getPart1()
    {
        return $this->part1;
    }

    /**
     * Set part2
     *
     * @param \AppBundle\Entity\Parte $part2
     *
     * @return Equivalencia
     */
    public function setPart2(\AppBundle\Entity\Parte $part2 = null)
    {
        $this->part2 = $part2;

        return $this;
    }

    /**
     * Get part2
     *
     * @return \AppBundle\Entity\Parte
     */
    public function getPart2()
    {
        return $this->part2;
    }
}
