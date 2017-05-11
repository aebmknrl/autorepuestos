<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equivalencia
 *
 * @ORM\Table(name="equivalencia")
 * @ORM\Entity
 */
class Equivalencia
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
     * @var string
     *
     * @ORM\Column(name="EQUIVALENCIA_REF", type="string", length=15, nullable=false)
     */
    private $equivalenciaRef;

    /**
     * @var string
     *
     * @ORM\Column(name="EQUIVALENCIA_DESC", type="string", length=45, nullable=true)
     */
    private $equivalenciaDesc;



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
     * Set equivalenciaDesc
     *
     * @param string $equivalenciaDesc
     *
     * @return Equivalencia
     */
    public function setEquivalenciaDesc($equivalenciaDesc)
    {
        $this->equivalenciaDesc = $equivalenciaDesc;

        return $this;
    }

    /**
     * Get equivalenciaDesc
     *
     * @return string
     */
    public function getEquivalenciaDesc()
    {
        return $this->equivalenciaDesc;
    }
}
