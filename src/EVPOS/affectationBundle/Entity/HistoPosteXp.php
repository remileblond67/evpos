<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoPosteXp
 *
 * @ORM\Table("evpos_histo_poste_xp")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\HistoPosteXpRepository")
 */
class HistoPosteXp
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateMesure", type="datetime")
     */
    private $dateMesure;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPosteXp", type="integer")
     */
    private $nbPosteXp;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPosteBorne", type="integer", options={"default" = 0})
     */
    private $nbPosteBorne;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPosteXpHr", type="integer", options={"default" = 0})
     */
    private $nbPosteXpHr;

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
     * Set dateMesure
     *
     * @param \DateTime $dateMesure
     *
     * @return HistoPosteXp
     */
    public function setDateMesure($dateMesure)
    {
        $this->dateMesure = $dateMesure;

        return $this;
    }

    /**
     * Get dateMesure
     *
     * @return \DateTime
     */
    public function getDateMesure()
    {
        return $this->dateMesure;
    }

    /**
     * Set nbPosteXp
     *
     * @param integer $nbPosteXp
     *
     * @return HistoPosteXp
     */
    public function setNbPosteXp($nbPosteXp)
    {
        $this->nbPosteXp = $nbPosteXp;

        return $this;
    }

    /**
     * Get nbPosteXp
     *
     * @return integer
     */
    public function getNbPosteXp()
    {
        return $this->nbPosteXp;
    }

    /**
     * Set nbPosteXpHr
     *
     * @param integer $nbPosteXpHr
     *
     * @return HistoPosteXpHr
     */
    public function setNbPosteXpHr($nbPosteXpHr)
    {
        $this->nbPosteXpHr = $nbPosteXpHr;

        return $this;
    }

    /**
     * Get nbPosteXpHr
     *
     * @return integer
     */
    public function getNbPosteXpHr()
    {
        return $this->nbPosteXpHr;
    }

    /**
     * Set nbPosteBorne
     *
     * @param integer $nbPosteBorne
     *
     * @return HistoPosteBorne
     */
    public function setNbPosteBorne($nbPosteBorne)
    {
        $this->nbPosteBorne = $nbPosteBorne;

        return $this;
    }

    /**
     * Get nbPosteBorne
     *
     * @return integer
     */
    public function getNbPosteBorne()
    {
        return $this->nbPosteBorne;
    }
}

