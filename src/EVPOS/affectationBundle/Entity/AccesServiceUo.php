<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * AccesServiceUo
 *
 * @ORM\Table("evpos_acces_service_uo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\AccesServiceUoRepository")
 */
class AccesServiceUo
{
    public function __construct() {
        // $this->dateImport = new DateTime();
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_import", type="datetime", nullable=true)
     */
    private $dateImport;

    /**
     * @var string
     *
     * @ORM\Column(name="source_import", type="string", length=30, nullable=true)
     */
    private $sourceImport;

    /**
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Service", inversedBy="listeAccesUo" )
     * @ORM\Id
     * @ORM\JoinColumn(name="code_service", referencedColumnName="code_service", nullable=false)
     */
    private $serviceAcces;
    
    /**
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\UO", inversedBy="listeServiceAcces")
     * @ORM\Id
     * @ORM\JoinColumn(name="code_uo", referencedColumnName="code_uo", nullable=false)
     */
    private $uoAcces;

    /**
     * Set dateImport
     *
     * @param \DateTime $dateImport
     * @return AccesUtilAppli
     */
    public function setDateImport($dateImport)
    {
        $this->dateImport = $dateImport;

        return $this;
    }

    /**
     * Get dateImport
     *
     * @return \DateTime 
     */
    public function getDateImport()
    {
        return $this->dateImport;
    }

    /**
     * Set source
     *
     * @param string $source
     * @return AccesUtilAppli
     */
    public function setSourceImport($source)
    {
        $this->sourceImport = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string 
     */
    public function getSourceImport()
    {
        return $this->sourceImport;
    }

    /**
     * Set serviceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\Service $serviceAcces
     * @return AccesServiceAppli
     */
    public function setServiceAcces(\EVPOS\affectationBundle\Entity\Service $serviceAcces)
    {
        $this->serviceAcces = $serviceAcces;

        return $this;
    }

    /**
     * Get serviceAcces
     *
     * @return \EVPOS\affectationBundle\Entity\Service 
     */
    public function getServiceAcces()
    {
        return $this->serviceAcces;
    }

    /**
     * Set appliAcces
     *
     * @param \EVPOS\affectationBundle\Entity\UO $uoAcces
     * @return AccesServiceUo
     */
    public function setUoAcces(\EVPOS\affectationBundle\Entity\UO $uoAcces)
    {
        $this->uoAcces = $uoAcces;

        return $this;
    }

    /**
     * Get appliAcces
     *
     * @return \EVPOS\affectationBundle\Entity\UO 
     */
    public function getUoAcces()
    {
        return $this->uoAcces;
    }
}
