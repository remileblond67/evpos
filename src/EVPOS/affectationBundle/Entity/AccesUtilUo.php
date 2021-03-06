<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccesUtilUo
 *
 * @ORM\Table("evpos_acces_util_uo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\AccesUtilUoRepository")
 */
class AccesUtilUo
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
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Utilisateur", inversedBy="listeAccesUo" )
     * @ORM\Id
     * @ORM\JoinColumn(name="mat_util", referencedColumnName="mat_util", nullable=false, onDelete="cascade")
     */
    private $utilAcces;

    /**
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\UO", inversedBy="listeAcces")
     * @ORM\Id
     * @ORM\JoinColumn(name="code_uo", referencedColumnName="code_uo", nullable=false, onDelete="cascade")
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
     * Set uoAcces
     *
     * @param \EVPOS\affectationBundle\Entity\UO $uoAcces
     * @return AccesUtilUo
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
