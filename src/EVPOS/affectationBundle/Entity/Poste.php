<?php

namespace EVPOS\affectationBundle\Entity;

use EVPOS\affectationBundle\Entity\Utilisateur;

use Doctrine\ORM\Mapping as ORM;

/**
 * Poste
 *
 * @ORM\Table("evpos_poste")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\PosteRepository")
 */
class Poste
{
    public function __construct() {
        $this->listeUtilisateurs = new ArrayCollection();
        $this->listeUo = new ArrayCollection();
        $this->listeEquipementLie = new ArrayCollection();
    }
    
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="hostname", type="string", length=10, nullable=true)
     */
    private $hostname;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="code_materiel", type="string", length=25)
     */
    private $codeMateriel;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255, nullable=true)
     */
    private $categorie;
    
    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=true)
     */
    private $modele;  
	
	/**
     * @var boolean
     *
     * @ORM\Column(name="licence_w8", type="boolean", nullable=true)
     */
    private $licenceW8;
    
    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255, nullable=true)
     */
    private $localisation;
    
    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=true)
     */
    private $statut; 

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;

    
    /**
     * @var string
     *
     * @ORM\Column(name="type_usage", type="string", length=255, nullable=true)
     */
    private $typeUsage;

    /**
    * @ORM\ManyToOne(targetEntity="Service", inversedBy="listePostes")
    * @ORM\JoinColumn(name="code_service", referencedColumnName="code_service", nullable=true)
    */
    private $service;    
    
    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur", inversedBy="listePostes")
     * @ORM\JoinTable(name="evpos_poste_util",
     *      joinColumns={@ORM\JoinColumn(name="hostname", referencedColumnName="hostname")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="mat_util", referencedColumnName="mat_util")}
     * )
     */
    private $listeUtilisateurs;
    
    /**
     * @ORM\ManyToMany(targetEntity="UO", inversedBy="listePostes")
     * @ORM\JoinTable(name="evpos_poste_uo",
     *      joinColumns={@ORM\JoinColumn(name="hostname", referencedColumnName="hostname")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="code_uo", referencedColumnName="code_uo")}
     * )
     */
    private $listeUo;
    
    /**
     * @ORM\OneToMany(targetEntity="Equipement", mappedBy="poste")
     */
    private $listeEquipement;
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getCodeMateriel()
    {
        return $this->codeMateriel;
    }
    
    public function setCodeMateriel($codeMateriel) {
        $this->codeMateriel = $codeMateriel;
        return $this;
    }

    /**
     * Set hostname
     *
     * @param string $hostname
     * @return Poste
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
     * Set type
     *
     * @param string $type
     * @return Poste
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return Poste
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    /**
     * Get categorie court (sans le préfixe)
     *
     * @return string 
     */
    public function getCategorieCourt()
    {
        $tmp = preg_replace('/^Matériel\/Ordinateur\/Poste client\/UC\//', '', $this->categorie);
        return $tmp;
    }
    

    /**
     * Set modele
     *
     * @param string $modele
     * @return Poste
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return string 
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set localisation
     *
     * @param string $localisation
     * @return Poste
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }

    /**
     * Get localisation
     *
     * @return string 
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }

    /**
     * Set statut
     *
     * @param string $statut
     * @return Poste
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Add listeUtilisateurs
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $listeUtilisateurs
     * @return Poste
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
     * Add listeUo
     *
     * @param \EVPOS\affectationBundle\Entity\UO $listeUo
     * @return Poste
     */
    public function addListeUo(\EVPOS\affectationBundle\Entity\UO $listeUo)
    {
        $this->listeUo[] = $listeUo;

        return $this;
    }

    /**
     * Remove listeUo
     *
     * @param \EVPOS\affectationBundle\Entity\UO $listeUo
     */
    public function removeListeUo(\EVPOS\affectationBundle\Entity\UO $listeUo)
    {
        $this->listeUo->removeElement($listeUo);
    }

    /**
     * Get listeUo
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListeUo()
    {
        return $this->listeUo;
    }
    
    /** 
     * Supprime la liste des UO installées sur le poste
     */
    public function delListeUo() {
        foreach ($this->listeUo as $uo) {
            $this->listeUo->removeElement($uo);
        }
    }

    /**
     * Set service
     *
     * @param \EVPOS\affectationBundle\Entity\Service $service
     * @return Poste
     */
    public function setService(\EVPOS\affectationBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \EVPOS\affectationBundle\Entity\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Poste
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set typeUsage
     *
     * @param string $typeUsage
     * @return Poste
     */
    public function setTypeUsage($typeUsage)
    {
        $this->typeUsage = $typeUsage;

        return $this;
    }

    /**
     * Get typeUsage
     *
     * @return string 
     */
    public function getTypeUsage()
    {
        return $this->typeUsage;
    }

    /**
     * Set licenceW8
     *
     * @param boolean $licenceW8
     * @return Poste
     */
    public function setLicenceW8($licenceW8)
    {
        $this->licenceW8 = $licenceW8;

        return $this;
    }

    /**
     * Get licenceW8
     *
     * @return boolean 
     */
    public function getLicenceW8()
    {
        return $this->licenceW8;
    }
    
    public function getLicenceW8Long() {
        switch ($this->licenceW8) {
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
   
    public function getLicenceW8Icon()
    {
        switch ($this->licenceW8) {
            case 1:
                $icon = "glyphicon glyphicon-ok ok";
                break;
            case 0:
                $icon = "glyphicon glyphicon-remove ko";
                break;
            default:
                $icon = "glyphicon glyphicon-minus";
        }
       
        return $icon;
    }
   

    /**
     * Add listeEquipement
     *
     * @param \EVPOS\affectationBundle\Entity\Equipement $listeEquipement
     * @return Poste
     */
    public function addListeEquipement(\EVPOS\affectationBundle\Entity\Equipement $listeEquipement)
    {
        $this->listeEquipement[] = $listeEquipement;

        return $this;
    }

    /**
     * Remove listeEquipement
     *
     * @param \EVPOS\affectationBundle\Entity\Equipement $listeEquipement
     */
    public function removeListeEquipement(\EVPOS\affectationBundle\Entity\Equipement $listeEquipement)
    {
        $this->listeEquipement->removeElement($listeEquipement);
    }

    /**
     * Get listeEquipement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getListeEquipement()
    {
        return $this->listeEquipement;
    }
}
