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
    * @ORM\Column(name="id_silo", type="integer")
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    */

    private $idSilo;

    /**
     * @var string
     * @ORM\Column(name="nomSilo", type="string", length=255, nullable=true)
     */
    private $nomSilo;

    private $listeUO;

    /**
    * Get idSilo
    *
    * @return integer
    */
    public function getId()
    {
        return $this->idSilo;
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
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeUO = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add listeUO
     *
     * @param \EVPOS\affectationBundle\Entity\UO $listeUO
     * @return Silo
     */
    public function addListeUO(\EVPOS\affectationBundle\Entity\UO $listeUO)
    {
        $this->listeUO[] = $listeUO;

        return $this;
    }

    /**
     * Remove listeUO
     *
     * @param \EVPOS\affectationBundle\Entity\UO $listeUO
     */
    public function removeListeUO(\EVPOS\affectationBundle\Entity\UO $listeUO)
    {
        $this->listeUO->removeElement($listeUO);
    }

    /**
     * Get listeUO
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeUO()
    {
        return $this->listeUO;
    }
}
