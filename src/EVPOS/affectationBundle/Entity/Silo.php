<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Silo
 *
 * @ORM\Table("evpos_silo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\SiloRepository")
 */
class Silo
{
    /**
    * @var integer
    *
    * @ORM\Column(name="id", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */

    private $id;

    /**
     * @var string
     * @ORM\Column(name="nomSilo", type="string", length=255, nullable=true)
     */
    private $nomSilo;

    /**
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\UO", inversedBy="listeSilo")
     * @ORM\Id
     * @ORM\JoinColumn(name="code_uo", referencedColumnName="code_uo", nullable=false, onDelete="cascade")
     */
    private $listeUO;

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
     * Set nomSilo
     *
     * @param string $nomSilo
     * @return Silo
     */
    public function setNomSilo($nomSilo)
    {
        $this->nomSilo = $nomSilo;

        return $this;
    }

    /**
     * Get nomSilo
     *
     * @return string
     */
    public function getNomSilo()
    {
        return $this->nomSilo;
    }
}
