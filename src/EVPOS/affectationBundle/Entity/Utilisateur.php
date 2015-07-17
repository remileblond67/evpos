<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\Sercice;
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
    * @ORM\ManyToOne(targetEntity="Service", inversedBy="listeUtilisateurs")
    * @ORM\JoinColumn(name="service_util", referencedColumnName="code_service", nullable=true)
    */
    private $serviceUtil;

    /**
     * @ORM\OneToMany(targetEntity="EVPOS\affectationBundle\Entity\AccesUtilAppli", mappedBy="utilAcces")
     */
    private $listeAcces;
    
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
}
