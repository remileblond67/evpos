<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\Service;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Utilisateur
 *
 * @ORM\Table("evpos_utilisateur")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\UtilisateurRepository")
 */
class Utilisateur
{
    public function __construct() {
        $this->listeAccesUo = new ArrayCollection() ;
		    $this->listeAcces = new ArrayCollection() ;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="mat_util", type="string", length=30, nullable=false)
     * @ORM\Id
     */
    private $matUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_util", type="string", length=255)
     */
    private $nomUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @ORM\Column(name="age_login", type="integer", nullable=true)
     */
    private $ageLogin;

    /**
    * @ORM\ManyToOne(targetEntity="Service", inversedBy="listeUtilisateurs")
    * @ORM\JoinColumn(name="service_util", referencedColumnName="code_service", nullable=true)
    */
    private $serviceUtil;

    /**
     * @var boolean
     *
     * @ORM\Column(name="existe_baza", type="boolean", nullable=true)
     */
    private $existeBaza;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesUtilAppli", mappedBy="utilAcces", cascade={"remove"})
     */
    private $listeAcces;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesUtilUo", mappedBy="utilAcces", cascade={"remove"})
     */
    private $listeAccesUo;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\Application", mappedBy="cpi", cascade={"remove"})
     * @ORM\JoinColumn(name="code_appli", referencedColumnName="code_appli")
     */
    private $listeAppliCpi;

    /**
     * @ORM\ManyToMany(targetEntity="Poste", mappedBy="listeUtilisateurs", cascade={"detach"})
     */
    private $listePostes;

    /**
     * @ORM\ManyToMany(targetEntity="Imprimante", inversedBy="listeUtilisateurs", cascade={"detach"})
     * @ORM\JoinTable(name="evpos_util_imprimante",
     *      joinColumns={@ORM\JoinColumn(name="mat_util", referencedColumnName="mat_util")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="printer_hostname", referencedColumnName="hostname")}
     * )
     */
    private $listeImprimantes;

    /**
     * Retourne le nombre d'applications auquel l'utilisateur a accès
     */
    public function getNbAcces() {
        return $this->listeAcces->count();
    }

    /**
     * Set matUtil
     *
     * @param string $matUtil
     * @return Utilisateur
     */
    public function setMatUtil($matUtil)
    {
        $this->matUtil = $matUtil;

        return $this;
    }

    /**
     * Get matUtil
     *
     * @return string
     */
    public function getMatUtil()
    {
        return $this->matUtil;
    }

    /**
     * Set nomUtil
     *
     * @param string $nomUtil
     * @return Utilisateur
     */
    public function setNomUtil($nomUtil)
    {
        $this->nomUtil = $nomUtil;

        return $this;
    }

    /**
     * Get nomUtil
     *
     * @return string
     */
    public function getNomUtil()
    {
        return $this->nomUtil;
    }

    /**
     * Set serviceUtil
     *
     * @param string $serviceUtil
     * @return Utilisateur
     */
    public function setServiceUtil($serviceUtil)
    {
        $this->serviceUtil = $serviceUtil;

        return $this;
    }

    /**
     * Get serviceUtil
     *
     * @return string
     */
    public function getServiceUtil()
    {
        return $this->serviceUtil;
    }

    public function getCodeService() {
      if ($this->getServiceUtil() !== NULL ) {
        $codeService = $this->getServiceUtil()->getCodeService();
      } else {
        $codeService = "";
      }
      return $codeService;
    }

    /**
     * Add listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilAppli $listeAcces
     * @return Utilisateur
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
     * Add listeAppliCpi
     *
     * @param \EVPOS\affectationBundle\Entity\Application $listeAppliCpi
     * @return Utilisateur
     */
    public function addListeAppliCpi(\EVPOS\affectationBundle\Entity\Application $listeAppliCpi)
    {
        $this->listeAppliCpi[] = $listeAppliCpi;

        return $this;
    }

    /**
     * Remove listeAppliCpi
     *
     * @param \EVPOS\affectationBundle\Entity\Application $listeAppliCpi
     */
    public function removeListeAppliCpi(\EVPOS\affectationBundle\Entity\Application $listeAppliCpi)
    {
        $this->listeAppliCpi->removeElement($listeAppliCpi);
    }

    /**
     * Get listeAppliCpi
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeAppliCpi()
    {
        return $this->listeAppliCpi;
    }

    /**
     * Add listeAccesUo
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilUo $listeAccesUo
     * @return Utilisateur
     */
    public function addListeAccesUo(\EVPOS\affectationBundle\Entity\AccesUtilUo $listeAccesUo)
    {
        $this->listeAccesUo[] = $listeAccesUo;

        return $this;
    }

    /**
     * Remove listeAccesUo
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUtilUo $listeAccesUo
     */
    public function removeListeAccesUo(\EVPOS\affectationBundle\Entity\AccesUtilUo $listeAccesUo)
    {
        $this->listeAccesUo->removeElement($listeAccesUo);
    }

    /**
     * Get listeAccesUo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeAccesUo()
    {
        return $this->listeAccesUo;
    }

    /**
     * Add listePostes
     *
     * @param \EVPOS\affectationBundle\Entity\Poste $listePostes
     * @return Utilisateur
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
     * Set lastLogin
     *
     * @param \DateTime $lastLogin
     * @return Utilisateur
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }

    /**
     * Get lastLogin
     *
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * Set existeBaza
     *
     * @param boolean $existeBaza
     * @return Utilisateur
     */
    public function setExisteBaza($existeBaza)
    {
        $this->existeBaza = $existeBaza;

        return $this;
    }

    /**
     * Get existeBaza
     *
     * @return boolean
     */
    public function getExisteBaza()
    {
        return $this->existeBaza;
    }

    /**
     * Set ageLogin
     *
     * @param integer $ageLogin
     * @return Utilisateur
     */
    public function setAgeLogin($ageLogin)
    {
        $this->ageLogin = $ageLogin;

        return $this;
    }

    /**
     * Get ageLogin
     *
     * @return integer
     */
    public function getAgeLogin()
    {
        return $this->ageLogin;
    }

    /**
     * Add listeImprimantes
     *
     * @param \EVPOS\affectationBundle\Entity\Imprimante $listeImprimantes
     * @return Utilisateur
     */
    public function addListeImprimante(\EVPOS\affectationBundle\Entity\Imprimante $listeImprimantes)
    {
        $this->listeImprimantes[] = $listeImprimantes;

        return $this;
    }

    /**
     * Remove listeImprimantes
     *
     * @param \EVPOS\affectationBundle\Entity\Imprimante $listeImprimantes
     */
    public function removeListeImprimante(\EVPOS\affectationBundle\Entity\Imprimante $listeImprimantes)
    {
        $this->listeImprimantes->removeElement($listeImprimantes);
    }

    /**
     * Get listeImprimantes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeImprimantes()
    {
        return $this->listeImprimantes;
    }

    /**
     * Supprime la liste des imprimantes de l'utilisateur
     */
    public function delListeImprimante() {
        foreach ($this->listeImprimantes as $imprimante) {
            $this->listeImprimantes->removeElement($imprimante);
        }
    }

    /**
     * REPORT DES imprimantes
     *
     * On reporte au niveau de l'utilisateur la liste des imprimantes
     * des postes utilisés
     */
    public function reportImprimantes() {
      $this->delListeImprimante();
      $listeImprimantes = [];

      // Récupération de la liste des postes utilisés
      foreach ($this->listePostes as $poste) {
        // Recherche de la liste des imprimantes utilisées
        foreach ($poste->getListeImprimantes() as $printer) {
          if (array_search($printer->gethostname(), $listeImprimantes) === false) {
            $this->addListeImprimante($printer);
            $listeImprimantes[] = $printer->gethostname();
          }
        }
      }
    }
}
