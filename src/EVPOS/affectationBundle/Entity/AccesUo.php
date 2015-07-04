<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccesUo
 *
 * @ORM\Table("evpos_acces_uo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\AccesUoRepository")
 */
class AccesUo
{
    public function __construct() {
        $this->dateAcces = new datetime();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Date du dernier accès constaté (date d'enregistrement de l'accès)
     * -----------------------------------------------------------------
     * @var \DateTime
     *
     * @ORM\Column(name="dateAcces", type="datetime")
     * @Assert\DateTime()
     */
    private $dateAcces;

    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumn(name="mat_util", referencedColumnName="mat_util", nullable=false)
     * @Assert\Length(min=5, message="Le matricule utilisateur doit faire au moins {{limit}} caractères")
     */
    private $matUtil;

    /**
     * @ORM\ManyToOne(targetEntity="UO")
     * @ORM\JoinColumn(name="code_uo", referencedColumnName="code_uo", nullable=false)
     * @Assert\Length(min=2, message="Le code UO doit faire au moins {{limit}} caractères")
     */
    private $codeUo;


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
     * Set dateAcces
     *
     * @param \DateTime $dateAcces
     * @return AccesUo
     */
    public function setDateAcces($dateAcces)
    {
        $this->dateAcces = $dateAcces;

        return $this;
    }

    /**
     * Get dateAcces
     *
     * @return \DateTime
     */
    public function getDateAcces()
    {
        return $this->dateAcces;
    }

    /**
     * Set matUtil
     *
     * @param \EVPOS\affectationBundle\Entity\Utilisateur $matUtil
     * @return AccesUo
     */
    public function setMatUtil(\EVPOS\affectationBundle\Entity\Utilisateur $matUtil)
    {
        $this->matUtil = $matUtil;

        return $this;
    }

    /**
     * Get matUtil
     *
     * @return \EVPOS\affectationBundle\Entity\Utilisateur
     */
    public function getMatUtil()
    {
        return $this->matUtil;
    }

    /**
     * Set codeUo
     *
     * @param \EVPOS\affectationBundle\Entity\UO $codeUo
     * @return AccesUo
     */
    public function setCodeUo(\EVPOS\affectationBundle\Entity\UO $codeUo)
    {
        $this->codeUo = $codeUo;

        return $this;
    }

    /**
     * Get codeUo
     *
     * @return \EVPOS\affectationBundle\Entity\UO
     */
    public function getCodeUo()
    {
        return $this->codeUo;
    }
}
