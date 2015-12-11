<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\Utilisateur;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Service
 *
 * @ORM\Table("evpos_service")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\ServiceRepository")
 */
class Service
{
    public function __construct()
    {
        $this->listeUtilisateurs = new ArrayCollection();
    }

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="code_service", type="string", length=5, nullable=false)
     */
    private $codeService;

    /**
     * @ORM\Column(name="nb_agent", type="integer", nullable=true)
     */
    private $nbAgent;

    /**
     * @ORM\Column(name="nb_poste", type="integer", nullable=true)
     */
    private $nbPoste;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_service", type="string", length=255)
     */
    private $libService;

    /**
     * @ORM\ManyToOne(targetEntity="Direction", inversedBy="listeServices", cascade={"persist", "detach"})
     * @ORM\JoinColumn(name="code_direction", referencedColumnName="code_direction",
     *                 nullable=true)
     */
    private $direction;

	/**
     * @ORM\ManyToMany(targetEntity="Utilisateur", cascade={"detach"})
     * @ORM\JoinTable(name="evpos_riu_service",
     *      joinColumns={@ORM\JoinColumn(name="service_riu", referencedColumnName="code_service")},
     *      inverseJoinColumns={
     *        @ORM\JoinColumn(name="mat_riu", referencedColumnName="mat_util", onDelete="cascade")
     *      }
     * )
	 */
	 private $listeRiu;

    /**
     * @ORM\OneToMany(targetEntity="Application", mappedBy="serviceAppli", cascade={"detach"})
     */
    private $listeAppliService;

    /**
     * @ORM\OneToMany(targetEntity="Utilisateur", mappedBy="serviceUtil", cascade={"persist", "remove"})
     */
    private $listeUtilisateurs;

    /**
     * @ORM\OneToMany(targetEntity="Poste", mappedBy="service", cascade={"remove"})
     */
    private $listePostes;
    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesServiceAppli", mappedBy="serviceAcces", cascade={"remove"})
     */
    private $listeAcces;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesServiceUo", mappedBy="serviceAcces", cascade={"remove"})
     */
    private $listeAccesUo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="existe_baza", type="boolean", nullable=true)
     */
    private $existeBaza;

    /**
     * Moyenne des notes des UO utilisées par le service, pondérée par leur nombre d'utilisateurs
     *
     * @var integer
     *
     * @ORM\Column(name="note_avancement_moca", type="integer", nullable=true)
     */
    private $noteAvancementMoca;

    /**
     * Retourne le nombre d'agents affectés au service
     */
    public function getNbAgent() {
        return $this->nbAgent;
    }

    public function setNbAgent($nb) {
      $this->nbAgent = $nb;
    }

    /**
     * Retourne le nombre d'accès applicatif affectés au service
     */
    public function getNbAcces() {
        return $this->listeAcces->count();
    }

	/**
     * Retourne le nombre d'accès UO affectés au service
     */
    public function getNbAccesUo() {
        return $this->listeAccesUo->count();
    }

    /**
     * Set codeService
     *
     * @param string $codeService
     * @return Service
     */
    public function setCodeService($codeService) {
        $this->codeService = $codeService;

        return $this;
    }

    /**
     * Get codeService
     *
     * @return string
     */
    public function getCodeService() {
        return $this->codeService;
    }

    /**
     * Set libService
     *
     * @param string $libService
     * @return Service
     */
    public function setLibService($libService) {
        $this->libService = $libService;

        return $this;
    }

    /**
     * Get libService
     *
     * @return string
     */
    public function getLibService() {
        return $this->libService;
    }

    /**
     * Add listeUtilisateurs
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs
     * @return Service
     */
    public function addListeUtilisateur(\EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs) {
        $this->listeUtilisateurs[] = $listeUtilisateurs;

        return $this;
    }

    /**
     * Remove listeUtilisateurs
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs
     */
    public function removeListeUtilisateur(\EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs)
    {
        $this->listeUtilisateurs->removeElement($listeUtilisateurs);
    }

    /**
     * Get listeUtilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeUtilisateurs()
    {
        return $this->listeUtilisateurs;
    }

    /**
     * Set codeDirection
     *
     * @param \EVPOS\affectationBundle\Entity\Direction $codeDirection
     * @return Service
     */
    public function setCodeDirection(\EVPOS\affectationBundle\Entity\Direction $codeDirection = null)
    {
        $this->codeDirection = $codeDirection;

        return $this;
    }

    /**
     * Get codeDirection
     *
     * @return \EVPOS\affectationBundle\Entity\Direction
     */
    public function getCodeDirection()
    {
        return $this->codeDirection;
    }

    /**
     * Set direction
     *
     * @param \EVPOS\affectationBundle\Entity\Direction $direction
     * @return Service
     */
    public function setDirection(\EVPOS\affectationBundle\Entity\Direction $direction = null)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return \EVPOS\affectationBundle\Entity\Direction
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Add listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces
     * @return Service
     */
    public function addListeAcces(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces)
    {
        $this->listeAcces[] = $listeAcces;

        return $this;
    }

    /**
     * Remove listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces
     */
    public function removeListeAcces(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces)
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
     * Add listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces
     * @return Service
     */
    public function addListeAcce(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces)
    {
        $this->listeAcces[] = $listeAcces;

        return $this;
    }

    /**
     * Remove listeAcces
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces
     */
    public function removeListeAcce(\EVPOS\affectationBundle\Entity\AccesServiceAppli $listeAcces)
    {
        $this->listeAcces->removeElement($listeAcces);
    }

    /**
     * Add listeRiu
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeRiu
     * @return Service
     */
    public function addListeRiu(\EVPOS\affectationBundle\Entity\Utilisateur $listeRiu)
    {
        $this->listeRiu[] = $listeRiu;

        return $this;
    }

    /**
     * Remove listeRiu
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeRiu
     */
    public function removeListeRiu(\EVPOS\affectationBundle\Entity\Utilisateur $listeRiu)
    {
        $this->listeRiu->removeElement($listeRiu);
    }

    /**
     * Get listeRiu
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeRiu()
    {
        return $this->listeRiu;
    }

    /**
     * Add listeAccesUo
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceUo $listeAccesUo
     * @return Service
     */
    public function addListeAccesUo(\EVPOS\affectationBundle\Entity\AccesServiceUo $listeAccesUo)
    {
        $this->listeAccesUo[] = $listeAccesUo;

        return $this;
    }

    /**
     * Remove listeAccesUo
     *
     * @param \EVPOS\affectationBundle\Entity\AccesServiceUo $listeAccesUo
     */
    public function removeListeAccesUo(\EVPOS\affectationBundle\Entity\AccesServiceUo $listeAccesUo)
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
     * Add listeAppliService
     *
     * @param \EVPOS\affectationBundle\Entity\Application $listeAppliService
     * @return Service
     */
    public function addListeAppliService(\EVPOS\affectationBundle\Entity\Application $listeAppliService)
    {
        $this->listeAppliService[] = $listeAppliService;

        return $this;
    }

    /**
     * Remove listeAppliService
     *
     * @param \EVPOS\affectationBundle\Entity\Application $listeAppliService
     */
    public function removeListeAppliService(\EVPOS\affectationBundle\Entity\Application $listeAppliService)
    {
        $this->listeAppliService->removeElement($listeAppliService);
    }

    /**
     * Get listeAppliService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeAppliService()
    {
        return $this->listeAppliService;
    }

    /**
     * Add listePostes
     *
     * @param \EVPOS\affectationBundle\Entity\Poste $listePostes
     * @return Service
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
     * Set existeBaza
     *
     * @param boolean $existeBaza
     * @return Service
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
     * Set noteAvancementMoca
     *
     * @param integer $noteAvancementMoca
     * @return Service
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
     * Set nbPoste
     *
     * @param integer $nbPoste
     * @return Service
     */
    public function setNbPoste($nbPoste)
    {
        $this->nbPoste = $nbPoste;

        return $this;
    }

    /**
     * Get nbPoste
     *
     * @return integer
     */
    public function getNbPoste()
    {
        return $this->nbPoste;
    }

    /**
     * Calcul de la note d'avancement du service
     */
    public function calculeNoteAvancement() {
      $sommeNote = 0;
      $nbUtil = 0;
      foreach ($this->listeAccesUo as $acces) {
          if ($acces->getUoAcces()->getMigMoca()) {
              $sommeNote += $acces->getUoAcces()->getNoteAvancementMoca() * $acces->getNbUtil();
              $nbUtil += $acces->getNbUtil();
          }
      }
      if ($nbUtil == 0) {
          $this->noteAvancementMoca = 100;
      } else {
          $this->noteAvancementMoca = round($sommeNote / $nbUtil);
      }
    }
}
