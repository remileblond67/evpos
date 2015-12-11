<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Secteur
 *
 * @ORM\Table("evpos_secteur")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\SecteurRepository")
 */
class Secteur
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="codeSecteur", type="string", length=25)
     */
    private $codeSecteur;

    /**
     * @var string
     *
     * @ORM\Column(name="libSecteur", type="string", length=255)
     */
    private $libSecteur;

    /**
     * @var integer
     *
     * @ORM\Column(name="note_avancement_moca", type="integer", nullable=true)
     */
    private $noteAvancementMoca;


    /**
     * @ORM\OneToMany(targetEntity="Application", mappedBy="secteur", cascade={"detach"})
     */
    private $listeAppli;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codeSecteur
     *
     * @param string $codeSecteur
     * @return Secteur
     */
    public function setCodeSecteur($codeSecteur)
    {
        $this->codeSecteur = $codeSecteur;

        return $this;
    }

    /**
     * Get codeSecteur
     *
     * @return string
     */
    public function getCodeSecteur()
    {
        return $this->codeSecteur;
    }

    /**
     * Set libSecteur
     *
     * @param string $libSecteur
     * @return Secteur
     */
    public function setLibSecteur($libSecteur)
    {
        $this->libSecteur = $libSecteur;

        return $this;
    }

    /**
     * Get libSecteur
     *
     * @return string
     */
    public function getLibSecteur()
    {
        return $this->libSecteur;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listeAppli = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add listeAppli
     *
     * @param \EVPOS\affectationBundle\Entity\Application $listeAppli
     * @return Secteur
     */
    public function addListeAppli(\EVPOS\affectationBundle\Entity\Application $listeAppli)
    {
        $this->listeAppli[] = $listeAppli;

        return $this;
    }

    /**
     * Remove listeAppli
     *
     * @param \EVPOS\affectationBundle\Entity\Application $listeAppli
     */
    public function removeListeAppli(\EVPOS\affectationBundle\Entity\Application $listeAppli)
    {
        $this->listeAppli->removeElement($listeAppli);
    }

    /**
     * Get listeAppli
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListeAppli()
    {
        return $this->listeAppli;
    }

    /**
     * Retourne la liste des applications informatiques
     *
     * @return \Doctrine\Common\Collections\Collection
     **/
    public function getListeAppliInfo() {
      $liste = [];
      foreach ($this->listeAppli as $appli) {
        if ($appli->getNatAppli() == "AI") {
          $liste[] = $appli;
        }
      }
      return $liste;
    }

    /**
     * Retourne la liste des applications informatiques
     *
     * @return \Doctrine\Common\Collections\Collection
     **/
    public function getListeAppliService() {
      $liste = [];
      foreach ($this->listeAppli as $appli) {
        if ($appli->getNatAppli() == "AS") {
          $liste[] = $appli;
        }
      }
      return $liste;
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
     * Calcul de la note d'avancement du secteur
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
     foreach ($this->listeAppli as $appli) {
       foreach ($appli->getListeUo() as $uo) {
         if ($uo->getMigMoca() == "1") {
           $nb = $uo->getListeAcces()->count();
           $nbUtil += $nb ;
           $nbUo++;
           $sommeNotePonderee += $uo->getNoteAvancementMoca() * $nb ;
           $sommeNote += $uo->getNoteAvancementMoca() ;
         }
       }
     }
     if ($nbUo != 0) {
       if ($nbUtil == 0) {
         // On fait simplement la moyenne de la note d'avancement des applications
         $this->noteAvancementMoca = $sommeNote / $nbUo ;
       } else {
         // On fait la moyenne pondérée en fonction du nombre d'utilisateur
         // de chaque application
         $this->noteAvancementMoca = $sommeNotePonderee / $nbUtil;
       }
     } else {
       $this->noteAvancementMoca = NULL;
     }
   }

}
