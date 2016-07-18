<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Silo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\SiloRepository")
 */
class Silo
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="nomSilo", type="string", length=255)
     */
    private $nomSilo;


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
