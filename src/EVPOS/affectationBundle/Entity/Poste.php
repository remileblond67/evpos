<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poste
 *
 * @ORM\Table("evpos_poste")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\PosteRepository")
 */
class Poste
{
    /**
     * @var integer
     *
     * @ORM\Column(name="code_materiel", type="string")
     * @ORM\Id
     */
    private $codeMateriel;

    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=8, nullable=true)
     */
    private $hostname;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getCodeMateriel()
    {
        return $this->codeMateriel;
    }
    
    public function setCodeMateriel($codeMateriel) {
        $this->codeMateriel = $codeMateriel;
        return $this;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Poste
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string 
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Poste
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
