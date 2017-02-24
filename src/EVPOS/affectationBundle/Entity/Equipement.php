<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Equipement
 *
 * @ORM\Table("evpos_equipement")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\EquipementRepository")
 */
class Equipement
{
    /**
     * @var string
     *
     * @ORM\Column(name="codeMateriel", type="string", length=255)
     * @ORM\Id
     */
    private $codeMateriel;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="modele", type="string", length=255, nullable=true)
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="marque", type="string", length=255, nullable=true)
     */
    private $marque;


    /**
     * @ORM\ManyToOne(targetEntity="Poste", inversedBy="listeEquipement")
     * @ORM\JoinColumn(name="hostname", referencedColumnName="hostname", nullable=false)
     */
    private $poste;

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
     * Set codeMateriel
     *
     * @param string $codeMateriel
     * @return MaterielLie
     */
    public function setCodeMateriel($codeMateriel)
    {
        $this->codeMateriel = $codeMateriel;

        return $this;
    }

    /**
     * Get codeMateriel
     *
     * @return string
     */
    public function getCodeMateriel()
    {
        return $this->codeMateriel;
    }

    /**
     * Set categorie
     *
     * @param string $categorie
     * @return MaterielLie
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
     * Set modele
     *
     * @param string $modele
     * @return MaterielLie
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
     * Set marque
     *
     * @param string $marque
     * @return MaterielLie
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * Get marque
     *
     * @return string
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set poste
     *
     * @param \EVPOS\affectationBundle\Entity\Poste $poste
     * @return EquipementLie
     */
    public function setPoste(\EVPOS\affectationBundle\Entity\Poste $poste = null)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return \EVPOS\affectationBundle\Entity\Poste
     */
    public function getPoste()
    {
        return $this->poste;
    }
}
