<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\UO;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application
 *
 * @ORM\Table("evpos_application")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\ApplicationRepository")
 */
class Application
{
    public function __construct() {
        $this->listeUo = new ArrayCollection() ;
        $this->listeAcces = new ArrayCollection() ;
        $this->listeServiceAcces = new ArrayCollection() ;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="code_appli", type="string", length=10, nullable=false)
     * @ORM\Id
     */
    private $codeAppli;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_appli", type="string", length=50, nullable=true)
     */
    private $nomAppli;

    /**
     * @var string
     *
     * @ORM\Column(name="desc_appli", type="string", length=2000, nullable=true)
     */
    private $descAppli;

    /**
     * Service en charge de l'application
     *
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Service", inversedBy="listeAppliService" )
     * @ORM\JoinColumn(name="service_appli", referencedColumnName="code_service")
     */
    private $serviceAppli;

    /**
     * @var boolean
     *
     * @ORM\Column(name="dispo_moca", type="boolean", nullable=true)
     */
    private $dispoMoca;

    /**
     * @var string
     *
     * @ORM\Column(name="nat_appli", type="string", length=2, nullable=true)
     */
    private $natAppli;

    /**
     * CPI de l'application (premier CPI récupéré de SUAPP)
     * Attention: si l'application comporte plusieurs CPI actifs, seul l'un d'eux est pris en compte
     *
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Utilisateur", inversedBy="listeAppliCpi" )
     * @ORM\JoinColumn(name="mat_cpi", referencedColumnName="mat_util")
     */
    private $cpi;

    /**
     * Secteur en charge de l'application
     * Attention: si l'application comporte plusieurs secteurs actifs, seul l'un d'eux est pris en compte
     * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Secteur", inversedBy="listeAppli" )
     * @ORM\JoinColumn(name="codeSecteur", referencedColumnName="codeSecteur")
     */
    private $secteur;

    /**
     * @var boolean
     *
     * @ORM\Column(name="existe_suapp", type="boolean", nullable=true)
     */
    private $existeSuapp;

    /**
     * @var integer
     *
     * @ORM\Column(name="note_avancement_moca", type="integer", nullable=true)
     */
    private $noteAvancementMoca;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\UO", mappedBy="appli", cascade={"persist", "remove"})
     */
    private $listeUO;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesUtilAppli", mappedBy="appliAcces", cascade={"persist", "remove"})
     */
    private $listeAcces;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesServiceAppli", mappedBy="appliAcces", cascade={"persist", "remove"})
     */
    private $listeServiceAcces;

    /**
     * Retourne le nombre d'utilisateurs qui ont accès à l'application
     */
    public function getNbAcces() {
        return $this->listeAcces->count();
    }

    /**
     * Set codeAppli
     *
     * @param string $codeAppli
     * @return Application
     */
    public function setCodeAppli($codeAppli)
    {
        $this->codeAppli = $codeAppli;

        return $this;
    }

    /**
     * Get codeAppli
     *
     * @return string
     */
    public function getCodeAppli()
    {
        return $this->codeAppli;
    }

    /**
     * Set nomAppli
     *
     * @param string $nomAppli
     * @return Application
     */
    public function setNomAppli($nomAppli)
    {
        $this->nomAppli = $nomAppli;

        return $this;
    }

    /**
     * Get nomAppli
     *
     * @return string
     */
    public function getNomAppli()
    {
        return $this->nomAppli;
    }

    /**
     * Set descAppli
     *
     * @param string $descAppli
     * @return Application
     */
    public function setDescAppli($descAppli)
    {
        $this->descAppli = $descAppli;

        return $this;
    }

    /**
     * Get descAppli
     *
     * @return string
     */
    public function getDescAppli()
    {
        return $this->descAppli;
    }

    /**
     * Add listeUO
     *
     * @param \EVPOS\affectationBundle\Entity\UO $listeUO
     * @return Application
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

    /**
     * Set natAppli
     *
     * @param string $natAppli
     * @return Application
     */
    public function setNatAppli($natAppli)
    {
        $this->natAppli = $natAppli;

        return $this;
    }

    /**
     * Get natAppli
     *
     * @return string
     */
    public function getNatAppli()
    {
        return $this->natAppli;
    }

    /**
     * Retourne la version longue de la nature appli
     *
     * @return string
     */
    public function getNatAppliLong() {
        $libNat="Autre";
        switch($this->natAppli) {
            case "AS":
                $libNat = "Application service";
                break;
            case "AI":
                $libNat = "Application informatique";
                break;
            default:
                $libNat = "?";
        }
        return $libNat;
    }

    /**
     * Set dispoMoca
     *
     * @param boolean $dispoMoca
     * @return Application
     */
    public function setDispoMoca($dispoMoca)
    {
        $this->dispoMoca = $dispoMoca;

        return $this;
    }

    public function getDispoMocaLong()
    {
        switch ($this->dispoMoca) {
            case 1:
                $retour = "Oui";
                break;
            default:
                $retour = "Non";
        }
        return $retour;
    }

    /**
     * Get dispoMoca
     *
     * @return boolean
     */
    public function getDispoMoca()
    {
        return $this->dispoMoca;
    }

    /**
     * Add listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilAppli $listeAcces
     * @return Application
     */
    public function addListeAcce(\EVPOS\affectationBundle\Entity\AccesUtilAppli $listeAcces)
    {
        $this->listeAcces[] = $listeAcces;

        return $this;
    }

    /**
     * Remove listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilAppli $listeAcces
     */
    public function removeListeAcce(\EVPOS\affectationBundle\Entity\AccesUtilAppli $listeAcces)
    {
        $this->listeAcces->removeElement($listeAcces);
    }

    /**
     * Get listeAcces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeAcces()
    {
        return $this->listeAcces;
    }

    /**
     * Set cpi
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $cpi
     * @return Application
     */
    public function setCpi(\EVPOS\affectationBundle\Entity\Utilisateur $cpi = null)
    {
        $this->cpi = $cpi;

        return $this;
    }

    /**
     * Get cpi
     *
     * @return \EVPOS\affectationBundle\Entity\Utilisateur
     */
    public function getCpi()
    {
        return $this->cpi;
    }

    /**
     * Add listeServiceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces
     * @return Application
     */
    public function addListeServiceAcces(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces)
    {
        $this->listeServiceAcces[] = $listeServiceAcces;

        return $this;
    }

    /**
     * Remove listeServiceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces
     */
    public function removeListeServiceAcces(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces)
    {
        $this->listeServiceAcces->removeElement($listeServiceAcces);
    }

    /**
     * Get listeServiceAcces
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeServiceAcces()
    {
        return $this->listeServiceAcces;
    }

    /**
     * Add listeServiceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces
     * @return Application
     */
    public function addListeServiceAcce(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces)
    {
        $this->listeServiceAcces[] = $listeServiceAcces;

        return $this;
    }

    /**
     * Remove listeServiceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces
     */
    public function removeListeServiceAcce(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeServiceAcces)
    {
        $this->listeServiceAcces->removeElement($listeServiceAcces);
    }

    /**
     * Set serviceAppli
     */
    public function setServiceAppli($codeAppli) {
        $this->serviceAppli = $codeAppli;
    }

    /**
     * Get serviceAppli
     */
    public function getServiceAppli() {
        return $this->serviceAppli;
    }

    /**
     * Set existeSuapp
     *
     * @param boolean $existeSuapp
     * @return Application
     */
    public function setExisteSuapp($existeSuapp)
    {
        $this->existeSuapp = $existeSuapp;

        return $this;
    }

    /**
     * Get existeSuapp
     *
     * @return boolean
     */
    public function getExisteSuapp()
    {
        return $this->existeSuapp;
    }

    /**
     * Set secteur
     *
     * @param \EVPOS\affectationBundle\Entity\Secteur $secteur
     * @return Application
     */
    public function setSecteur(\EVPOS\affectationBundle\Entity\Secteur $secteur = null)
    {
        $this->secteur = $secteur;

        return $this;
    }

    /**
     * Get secteur
     *
     * @return \EVPOS\affectationBundle\Entity\Secteur
     */
    public function getSecteur()
    {
        return $this->secteur;
    }

    /**
     * Set noteAvancementMoca
     *
     * @param integer $noteAvancementMoca
     * @return UO
     */
    public function setNoteAvancementMoca($noteAvancementMoca)
    {
        $this->noteAvancementMoca = $noteAvancementMoca;

        return $this;
    }

    /**
     * Get noteAvancementMoca
     *
     * @return integer
     */
    public function getNoteAvancementMoca()
    {
        return $this->noteAvancementMoca;
    }
}
