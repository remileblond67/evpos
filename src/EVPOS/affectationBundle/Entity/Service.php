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
     * @ORM\ManyToOne(targetEntity="Direction", inversedBy="listeServices")
     * @ORM\JoinColumn(name="code_direction", referencedColumnName="code_direction", nullable=true)
     */
    private $direction;

     /**
      * @ORM\OneToMany(targetEntity="Utilisateur", mappedBy="serviceUtil")
      */
    private $listeUtilisateurs;
    
    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesServiceAppli", mappedBy="serviceAcces")
     */
    private $listeAcces;

    /**
     * Set codeService
     *
     * @param string $codeService
     * @return Service
     */
    public function setCodeService($codeService)
    {
        $this->codeService = $codeService;

        return $this;
    }

    /**
     * Get codeService
     *
     * @return string
     */
    public function getCodeService()
    {
        return $this->codeService;
    }

    /**
     * Set libService
     *
     * @param string $libService
     * @return Service
     */
    public function setLibService($libService)
    {
        $this->libService = $libService;

        return $this;
    }

    /**
     * Get libService
     *
     * @return string
     */
    public function getLibService()
    {
        return $this->libService;
    }

    /**
     * Add listeUtilisateurs
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs
     * @return Service
     */
    public function addListeUtilisateur(\EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs)
    {
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
}
