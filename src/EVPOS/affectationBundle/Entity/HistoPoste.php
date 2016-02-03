<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistPoste
 *
 * @ORM\Table("evpos_histo_poste")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\HistoPosteRepository")
 */
class HistoPoste
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
     * @var integer
     *
     * @ORM\Column(name="nbPosteMoca", type="integer")
     */
    private $nbPosteMoca;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbPosteTodo", type="integer")
     */
    private $nbPosteTodo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMesure", type="datetime")
     */
    private $dateMesure;


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
     * Set nbPosteMoca
     *
     * @param integer $nbPosteMoca
     * @return HistPoste
     */
    public function setNbPosteMoca($nbPosteMoca)
    {
        $this->nbPosteMoca = $nbPosteMoca;

        return $this;
    }

    /**
     * Get nbPosteMoca
     *
     * @return integer
     */
    public function getNbPosteMoca()
    {
        return $this->nbPosteMoca;
    }

    /**
     * Set nbPosteTodo
     *
     * @param integer $nbPosteTodo
     * @return HistPoste
     */
    public function setNbPosteTodo($nbPosteTodo)
    {
        $this->nbPosteTodo = $nbPosteTodo;

        return $this;
    }

    /**
     * Get nbPosteTodo
     *
     * @return integer
     */
    public function getNbPosteTodo()
    {
        return $this->nbPosteTodo;
    }

    /**
     * Set dateMesure
     *
     * @param \DateTime $dateMesure
     * @return HistPoste
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
}
