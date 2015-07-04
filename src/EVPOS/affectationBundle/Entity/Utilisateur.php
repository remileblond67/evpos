<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\Sercice;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="mat_util", type="string", length=10, nullable=false)
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
     * @ORM\Column(name="prenom_util", type="string", length=255, nullable=true)
     */
    private $prenomUtil;

    /**
    * @ORM\ManyToOne(targetEntity="Service", inversedBy="listeUtilisateurs")
    * @ORM\JoinColumn(name="service_util", referencedColumnName="code_service", nullable=true)
    */
    private $serviceUtil;


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
     * Set prenomUtil
     *
     * @param string $prenomUtil
     * @return Utilisateur
     */
    public function setPrenomUtil($prenomUtil)
    {
        $this->prenomUtil = $prenomUtil;

        return $this;
    }

    /**
     * Get prenomUtil
     *
     * @return string
     */
    public function getPrenomUtil()
    {
        return $this->prenomUtil;
    }

    /**
     * Add listeAccesUo
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUo $listeAccesUo
     * @return Utilisateur
     */
    public function addListeAccesUo(\EVPOS\affectationBundle\Entity\AccesUo $listeAccesUo)
    {
        $this->listeAccesUo[] = $listeAccesUo;

        return $this;
    }

    /**
     * Remove listeAccesUo
     *
     * @param \EVPOS\affectationBundle\Entity\AccesUo $listeAccesUo
     */
    public function removeListeAccesUo(\EVPOS\affectationBundle\Entity\AccesUo $listeAccesUo)
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
}
