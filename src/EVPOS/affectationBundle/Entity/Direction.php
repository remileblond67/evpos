<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Direction
 *
 * @ORM\Table("evpos_direction")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\DirectionRepository")
 */
class Direction
{
    /**
     * @var string
     *
     * @ORM\Column(name="code_direction", type="string", length=255)
     * @ORM\Id
     */
    private $codeDirection;

    /**
     * @var string
     *
     * @ORM\Column(name="lib_direction", type="string", length=255, nullable=true)
     */
    private $libDirection;

    /**
     * @ORM\Column(name="nb_agent", type="integer", nullable=true)
     */
    private $nbAgent;

    /**
     * @ORM\Column(name="nb_poste", type="integer", nullable=true)
     */
    private $nbPoste;


    /**
     * @ORM\OneToMany(targetEntity="Service", mappedBy="direction")
     */
    private $listeServices;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="mat_criu", referencedColumnName="mat_util", nullable=true)
     */
    private $criu;

    /**
     * @var boolean
     *
     * @ORM\Column(name="existe_baza", type="boolean", nullable=true)
     */
    private $existeBaza;

    /**
     * Moyenne des notes d'avancement des services, pondérée par leur nombre d'agents
     *
     * @var integer
     *
     * @ORM\Column(name="note_avancement_moca", type="integer", nullable=true)
     */
    private $noteAvancementMoca;

    /**
     * Set codeDirection
     *
     * @param string $codeDirection
     * @return Direction
     */
    public function setCodeDirection($codeDirection)
    {
        $this->codeDirection = $codeDirection;

        return $this;
    }

    /**
     * Get codeDirection
     *
     * @return string
     */
    public function getCodeDirection()
    {
        return $this->codeDirection;
    }

    /**
     * Set libDirection
     *
     * @param string $libDirection
     * @return Direction
     */
    public function setLibDirection($libDirection)
    {
        $this->libDirection = $libDirection;

        return $this;
    }

    /**
     * Get libDirection
     *
     * @return string
     */
    public function getLibDirection()
    {
        return $this->libDirection;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeServices = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add listeServices
     *
     * @param \EVPOS\affectationBundle\Entity\Service $listeServices
     * @return Direction
     */
    public function addListeService(\EVPOS\affectationBundle\Entity\Service $listeServices)
    {
        $this->listeServices[] = $listeServices;

        return $this;
    }

    /**
     * Remove listeServices
     *
     * @param \EVPOS\affectationBundle\Entity\Service $listeServices
     */
    public function removeListeService(\EVPOS\affectationBundle\Entity\Service $listeServices)
    {
        $this->listeServices->removeElement($listeServices);
    }

    /**
     * Get listeServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeServices()
    {
        return $this->listeServices;
    }

    /**
     * Set criu
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $criu
     * @return Direction
     */
    public function setCriu(\EVPOS\affectationBundle\Entity\Utilisateur $criu = null)
    {
        $this->criu = $criu;

        return $this;
    }

    /**
     * Get criu
     *
     * @return \EVPOS\affectationBundle\Entity\Utilisateur
     */
    public function getCriu()
    {
        return $this->criu;
    }

    /**
     * Set existeBaza
     *
     * @param boolean $existeBaza
     * @return Direction
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
     * @return Direction
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
     * Set nbAgent
     *
     * @param integer $nbAgent
     * @return Direction
     */
    public function setNbAgent($nbAgent)
    {
        $this->nbAgent = $nbAgent;

        return $this;
    }

    /**
     * Get nbAgent
     *
     * @return integer
     */
    public function getNbAgent()
    {
        return $this->nbAgent;
    }

    /**
     * Set nbPoste
     *
     * @param integer $nbPoste
     * @return Direction
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
}
