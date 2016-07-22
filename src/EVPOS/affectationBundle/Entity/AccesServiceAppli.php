<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccesServiceAppli
 *
 * @ORM\Table("evpos_acces_service_appli")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\AccesServiceAppliRepository")
 */
class AccesServiceAppli
{
    public function __construct() {
        //$this->dateImport = new DateTime();
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
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Service", inversedBy="listeAcces" )
     * @ORM\Id
     * @ORM\JoinColumn(name="code_service", referencedColumnName="code_service", nullable=false)
     */
    private $serviceAcces;

    /**
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Application", inversedBy="listeServiceAcces")
     * @ORM\Id
     * @ORM\JoinColumn(name="code_appli", referencedColumnName="code_appli", nullable=false)
     */
    private $appliAcces;

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
     * @param \EVPOS\affectationBundle\Entity\Application $appliAcces
     * @return AccesServiceAppli
     */
    public function setAppliAcces(\EVPOS\affectationBundle\Entity\Application $appliAcces)
    {
        $this->appliAcces = $appliAcces;

        return $this;
    }

    /**
     * Get appliAcces
     *
     * @return \EVPOS\affectationBundle\Entity\Application
     */
    public function getAppliAcces()
    {
        return $this->appliAcces;
    }
}
