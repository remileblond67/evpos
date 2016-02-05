<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoUo
 *
 * @ORM\Table("evpos_histo_uo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\HistoUoRepository")
 */
class HistoUo
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
     * @ORM\Column(name="datemesure", type="datetime")
     */
    private $dateMesure;

    /**
     * @var string
     *
     * @ORM\Column(name="natureAppli", type="string", length=2)
     */
    private $natureAppli;

    /**
     * @var string
     *
     * @ORM\Column(name="avancement", type="string", length=255)
     */
    private $avancement;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbUo", type="integer")
     */
    private $nbUo;

    /**
     * @var string
     *
     * @ORM\Column(name="niveau", type="string", length=10)
     */
    private $niveau;

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
     * Set datemesure
     *
     * @param \DateTime $dateMesure
     * @return HistoUo
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
     * Set natureAppli
     *
     * @param string $natureAppli
     * @return HistoUo
     */
    public function setNatureAppli($natureAppli)
    {
        $this->natureAppli = $natureAppli;

        return $this;
    }

    /**
     * Get natureAppli
     *
     * @return string
     */
    public function getNatureAppli()
    {
        return $this->natureAppli;
    }

    /**
     * Set avancement
     *
     * @param string $avancement
     * @return HistoUo
     */
    public function setAvancement($avancement)
    {
        $this->avancement = $avancement;

        return $this;
    }

    /**
     * Get avancement
     *
     * @return string
     */
    public function getAvancement()
    {
        return $this->avancement;
    }

    /**
     * Set nbUo
     *
     * @param integer $nbUo
     * @return HistoUo
     */
    public function setNbUo($nbUo)
    {
        $this->nbUo = $nbUo;

        return $this;
    }

    /**
     * Get nbUo
     *
     * @return integer
     */
    public function getNbUo()
    {
        return $this->nbUo;
    }

    /**
     * Set niveau
     *
     * @param string $niveau
     * @return HistoUo
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }
}
