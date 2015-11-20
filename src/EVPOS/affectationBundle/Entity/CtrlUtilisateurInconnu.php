<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtrlUtilisateurInconnu
 *
 * @ORM\Table("evpos_utilisateur_inconnu")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\CtrlUtilisateurInconnuRepository")
 */
class CtrlUtilisateurInconnu
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="matUtil", type="string", length=30)
     */
    private $matUtil;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=255, nullable=TRUE)
     */
    private $commentaire;
    
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
     * Set matUtil
     *
     * @param string $matUtil
     * @return CtrlUtilisateurInconnu
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
     * Set commentaire
     *
     * @param string $commentaire
     * @return CtrlUtilisateurInconnu
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
}
