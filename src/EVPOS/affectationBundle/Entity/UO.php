<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Uo
 *
 * @ORM\Table("evpos_uo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\UORepository")
 */
class UO
{
    public function __construct() {
        $this->listeUtilAcces = new ArrayCollection() ;
    }
    
    /**
    * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\Application", inversedBy="listeUO")
    * @ORM\JoinColumn(name="code_appli", referencedColumnName="code_appli", nullable=false)
    */
    private $appli;

    /**
     * @var string
     *
     * @ORM\Column(name="code_uo", type="string", length=12, nullable=false)
     * @ORM\Id
     */
    private $codeUo;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_uo", type="string", length=50, nullable=true)
     */
    private $nomUo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="mig_moca", type="boolean", nullable=true)
     */
    private $migMoca;

    /**
     * @var boolean
     *
     * @ORM\Column(name="avancement_moca", type="string", length=50, nullable=true)
     */
    private $avancementMoca;
    
    /** 
     * @var string
     * @ORM\Column(name="type_poste", type="string", length=50, nullable=true)
     */
    private $typePoste;
    
    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesUtilUo", mappedBy="uoAcces", cascade={"persist", "remove"})
     */
    private $listeAcces;
       
    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesServiceUo", mappedBy="uoAcces", cascade={"persist", "remove"})
     */
    private $listeServiceAcces;
    
    /**
     * @ORM\ManyToMany(targetEntity="Poste", mappedBy="listeUo")
     */
    private $listePostes;

    /**
     * Set codeUo
     *
     * @param string $codeUo
     * @return UO
     */
    public function setCodeUo($codeUo)
    {
        $this->codeUo = $codeUo;

        return $this;
    }

    /**
     * Get codeUo
     *
     * @return string
     */
    public function getCodeUo()
    {
        return $this->codeUo;
    }

    /**
     * Set nomUo
     *
     * @param string $nomUo
     * @return UO
     */
    public function setNomUo($nomUo)
    {
        $this->nomUo = $nomUo;

        return $this;
    }

    /**
     * Get nomUo
     *
     * @return string
     */
    public function getNomUo()
    {
        return $this->nomUo;
    }

    /**
     * Set appli
     *
     * @param \EVPOS\affectationBundle\Entity\Application $appli
     * @return UO
     */
    public function setAppli(\EVPOS\affectationBundle\Entity\Application $appli)
    {
        $this->appli = $appli;

        return $this;
    }

    /**
     * Get appli
     *
     * @return \EVPOS\affectationBundle\Entity\Application
     */
    public function getAppli()
    {
        return $this->appli;
    }

    /**
     * Set migMoca
     *
     * @param boolean $migMoca
     * @return UO
     */
    public function setMigMoca($migMoca)
    {
        $this->migMoca = $migMoca;

        return $this;
    }

    /**
     * Get migMoca
     *
     * @return boolean
     */
    public function getMigMoca()
    {
        return $this->migMoca;
    }

    public function getMigMocaLong()
    {
        switch ($this->migMoca) {
            case 1:
                $retour = "Oui";
                break;
            case 0:
                $retour = "Non";
                break;
            default:
                $retour = "";
        }
        return $retour;
    }

    /**
     * Set avancementMoca
     *
     * @param boolean $avancementMoca
     * @return UO
     */
    public function setAvancementMoca($avancementMoca)
    {
        $this->avancementMoca = $avancementMoca;

        return $this;
    }

    /**
     * Get avancementMoca
     *
     * @return boolean
     */
    public function getAvancementMoca()
    {
        return $this->avancementMoca;
    }
    
    /**
     * Get avancementMocaIcon
     *
     * @return boolean
     */
    public function getAvancementMocaIcon()
    {
        switch ($this->avancementMoca) {
            case "3. Validé":
                $icon = "glyphicon glyphicon-ok ok";
                break;
            case "2. En cours":
                $icon = "glyphicon glyphicon-refresh";
                break;
            case "1. Pas initiée":
                $icon = "glyphicon glyphicon-remove ko";
                break;
            default:
                $icon = "glyphicon glyphicon-minus";
        }
        
        return $icon;
    }
    
    public function getEnCours() {
        switch ($this->avancementMoca) {
            case "3. Validé":
                $enCours = true;
                break;
            case "2. En cours":
                $enCours = true;
                break;
            default:
                $enCours = false;
        }
        return $enCours;
    }
    

    /**
     * Add listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilUo $listeAcces
     * @return UO
     */
    public function addListeAcce(\EVPOS\affectationBundle\Entity\AccesUtilUo $listeAcces)
    {
        $this->listeAcces[] = $listeAcces;

        return $this;
    }

    /**
     * Remove listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilUo $listeAcces
     */
    public function removeListeAcce(\EVPOS\affectationBundle\Entity\AccesUtilUo $listeAcces)
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
     * Add listeServiceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceUo $listeServiceAcces
     * @return UO
     */
    public function addListeServiceAcce(\EVPOS\affectationBundle\Entity\AccesServiceUo $listeServiceAcces)
    {
        $this->listeServiceAcces[] = $listeServiceAcces;

        return $this;
    }

    /**
     * Remove listeServiceAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceUo $listeServiceAcces
     */
    public function removeListeServiceAcce(\EVPOS\affectationBundle\Entity\AccesServiceUo $listeServiceAcces)
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
    
    public function setTypePoste($type) {
        $this->typePoste = $type;
    }
    
    public function getTypePoste() {
        return $this->typePoste;
    }
    
    /**
     * Ajoute un nouveau type de poste pour l'UO
     */
    public function appendTypePoste($type) {
        $this->typePoste = $this->typePoste . " " . $type;
    }

    /**
     * Add listePostes
     *
     * @param \EVPOS\affectationBundle\Entity\Poste $listePostes
     * @return UO
     */
    public function addListePoste(\EVPOS\affectationBundle\Entity\Poste $listePostes)
    {
        $this->listePostes[] = $listePostes;

        return $this;
    }

    /**
     * Remove listePostes
     *
     * @param \EVPOS\affectationBundle\Entity\Poste $listePostes
     */
    public function removeListePoste(\EVPOS\affectationBundle\Entity\Poste $listePostes)
    {
        $this->listePostes->removeElement($listePostes);
    }

    /**
     * Get listePostes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListePostes()
    {
        return $this->listePostes;
    }
    
    /**
     * Retourne le lien vers la FIA de l'application
     */
    public function getLienFia() {
        return "https://sharecan.strasbourg.eu/projets/moca/Applications/".$this->codeUo."_FIA.docx";
    }
     
}
