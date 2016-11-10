<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquivalenciaV2
 *
 * @ORM\Table(name="equivalencia_v2")
 * @ORM\Entity
 */
class EquivalenciaV2
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
     * @ORM\Column(name="equivalencia_ref", type="string", length=15, nullable=false)
     */
    private $equivalenciaRef;

    /**
     * @var integer
     *
     * @ORM\Column(name="part1_id", type="integer", nullable=false)
     */
    private $part1Id;

    /**
     * @var integer
     *
     * @ORM\Column(name="part2_id", type="integer", nullable=false)
     */
    private $part2Id;



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
     * @return EquivalenciaV2
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
     * Set part1Id
     *
     * @param integer $part1Id
     *
     * @return EquivalenciaV2
     */
    public function setPart1Id($part1Id)
    {
        $this->part1Id = $part1Id;

        return $this;
    }

    /**
     * Get part1Id
     *
     * @return integer
     */
    public function getPart1Id()
    {
        return $this->part1Id;
    }

    /**
     * Set part2Id
     *
     * @param integer $part2Id
     *
     * @return EquivalenciaV2
     */
    public function setPart2Id($part2Id)
    {
        $this->part2Id = $part2Id;

        return $this;
    }

    /**
     * Get part2Id
     *
     * @return integer
     */
    public function getPart2Id()
    {
        return $this->part2Id;
    }
}
