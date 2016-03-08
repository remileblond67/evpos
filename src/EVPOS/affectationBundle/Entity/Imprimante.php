<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Imprimante
 *
 * @ORM\Table("evpos_imprimante")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\ImprimanteRepository")
 */
class Imprimante
{
    /**
     * @var string
     *
     * @ORM\Column(name="hostname", type="string", length=25)
     * @ORM\Id
     */
    private $hostname;

    /**
     * @ORM\ManyToMany(targetEntity="Poste", mappedBy="listeImprimantes", cascade={"detach"})
     */
    private $listePostes;

    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur", mappedBy="listeImprimantes", cascade={"detach"})
     */
    private $listeUtilisateurs;

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Imprimante
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;

        return $this;
    }

    /**
     * Get hostname
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listePostes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add listePostes
     *
     * @param \EVPOS\affectationBundle\Entity\Poste $listePostes
     * @return Imprimante
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
     * Add listeUtilisateurs
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs
     * @return Imprimante
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

    
}
