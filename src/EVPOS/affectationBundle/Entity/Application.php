<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\UO;
use EVPOS\affectationBundle\Entity\Utilisateur;
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
     * Retourne la liste des numéros d'ensemble utilisant l'application
     */
    public function getListeEnsembles() {
      $listeEnsemble = [] ;
      foreach ($this->listeServiceAcces as $service) {
        $listeEnsemble[] = $service->getServiceAcces()->getNumEnsemble();
      }
      return sort(array_unique($listeEnsemble));
    }

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

  /**
   * CALCUL DE LA NOTE D'AVANCEMENT DE L'INTÉGRATION DE L'APPLICATION
   * Cette note est calculée de la façon suivante :
   * - si les UO de l'application comportent des utilisateurs, la note est
   *   à la moyenne de la note des UO pondérée par le nombre d'utilisateurs
   * - si aucune UO ne comportent d'utilisateurs, on calcule une moyenne
   *   simple des notes des UO
   */
  public function calculeNoteAvancement() {
    // Somme des notes
    $sommeNote = 0;
    // Somme des notes pondérées par le nombre d'utilisateurs
    $sommeNotePonderee = 0;
    // Somme des nombres d'utilisateurs des UO de l'application
    $nbUtil = 0;
    // Nombre d'UO de l'application
    $nbUo = 0;
    foreach ($this->listeUO as $uo) {
      if ($uo->getMigMoca() == "1") {
        $nb = $uo->getListeAcces()->count();
        $nbUtil += $nb ;
        $nbUo++;
        $sommeNotePonderee += $uo->getNoteAvancementMoca() * $nb ;
        $sommeNote += $uo->getNoteAvancementMoca() ;
      }
    }
    if ($nbUo != 0) {
      if ($nbUtil == 0) {
        // On fait simplement la moyenne de la note d'avancement des UO
        $this->noteAvancementMoca = $sommeNote / $nbUo ;
      } else {
        // On fait la moyenne pondérée en fonction du nombre d'utilisateur
        // de chaque UO
        $this->noteAvancementMoca = $sommeNotePonderee / $nbUtil;
      }
    } else {
      // L'application n'a aucune UO
      $this->noteAvancementMoca = 0;
    }
  }

  /**
   * REPORT DES ACCÈS UO SUR LES APPLICATIONS
   *
   * On reporte au niveau de l'application la liste des utilisateurs des UO
   * actives de cette dernière
   */
  public function reportAccesUo() {
    $listeUtilAppli = [];

    // Récupération de la liste des utilisateurs de l'application
    foreach ($this->listeAcces as $acces) {
      $util = $acces->getUtilAcces();
      $listeUtilAppli[$util->getMatUtil()] = $util;
    }

    // Récupération de la liste des matricules des utilisateurs d'UO actives
    foreach ($this->listeUO as $uo) {
      if ($uo->getMigMoca()) {
        foreach ($uo->getListeAcces() as $acces) {
          $utilAcces = $acces->getUtilAcces();
          // Si l'utilisateur de l'UO ne se retrouve pas déjà dans l'application
          if (array_search($utilAcces->getMatUtil(), array_keys($listeUtilAppli)) === FALSE) {
            $listeUtilAppli[$utilAcces->getMatUtil()] = $utilAcces;

            // Ajout de l'utilisateur de l'UO à la liste des utilisateurs de l'application
            $newAcces = new AccesUtilAppli();
            $newAcces->setSourceImport("Report accès UO");
            $newAcces->setUtilAcces($utilAcces);
            $newAcces->setAppliAcces($this);

            $this->listeAcces[] = $newAcces;
            unset($newAcces);
          }
          unset($utilAcces);
        }
      }
    }
    unset($listeUtilAppli);
  }
}
