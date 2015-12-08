<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccesUtilAppli
 *
 * @ORM\Table("evpos_acces_util_appli")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\AccesUtilAppliRepository")
 */
class AccesUtilAppli
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
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Utilisateur", inversedBy="listeAcces" )
     * @ORM\Id
     * @ORM\JoinColumn(name="mat_util", referencedColumnName="mat_util", nullable=false, onDelete="cascade")
     */
    private $utilAcces;

    /**
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Application", inversedBy="listeAcces")
     * @ORM\Id
     * @ORM\JoinColumn(name="code_appli", referencedColumnName="code_appli", nullable=false, onDelete="cascade")
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
     * Set utilAcces
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $utilAcces
     * @return AccesUtilAppli
     */
    public function setUtilAcces(\EVPOS\affectationBundle\Entity\Utilisateur $utilAcces)
    {
        $this->utilAcces = $utilAcces;

        return $this;
    }

    /**
     * Get utilAcces
     *
     * @return \EVPOS\affectationBundle\Entity\Utilisateur
     */
    public function getUtilAcces() {
        return $this->utilAcces;
    }

    /**
     * Set appliAcces
     *
     * @param \EVPOS\affectationBundle\Entity\Application $appliAcces
     * @return AccesUtilAppli
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
