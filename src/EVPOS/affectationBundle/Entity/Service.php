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
     * @var string
     *
     * @ORM\Column(name="lib_service", type="string", length=255)
     */
    private $libService;

    /**
     * @ORM\ManyToOne(targetEntity="Direction", inversedBy="listeServices", cascade={"persist"})
     * @ORM\JoinColumn(name="code_direction", referencedColumnName="code_direction",
     *                 nullable=true)
     */
    private $direction;
	
	/**
     * @ORM\ManyToMany(targetEntity="Utilisateur")
     * @ORM\JoinTable(name="evpos_riu_service",
     *      joinColumns={@ORM\JoinColumn(name="service_riu", referencedColumnName="code_service")},
     *      inverseJoinColumns={
     *      @ORM\JoinColumn(name="mat_riu", referencedColumnName="mat_util")
     *      }
     * )
	 */
	private $listeRiu;

     /**
      * @ORM\OneToMany(targetEntity="Utilisateur", mappedBy="serviceUtil")
      */
    private $listeUtilisateurs;
    
    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesServiceAppli", mappedBy="serviceAcces")
     */
    private $listeAcces;

    /**
     * Retourne le nombre d'agents affectés au service
     */
    public function getNbAgent() {
        return $this->listeUtilisateurs->count();
    }
    
    /**
     * Retourne le nombre d'accès applicatif affectés au service
     */
    public function getNbAcces() {
        return $this->listeAcces->count();
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
}
