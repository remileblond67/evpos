<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CtrlUoInconnue
 *
 * @ORM\Table("evpos_ctrl_uo_inconnue")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\CtrlUoInconnueRepository")
 */
class CtrlUoInconnue
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
     * @ORM\Column(name="codeUo", type="string", length=50)
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
     * Set codeUo
     *
     * @param string $codeUo
     * @return UoInconnue
     */
    public function setCodeUo($codeUo)
    {
        $this->codeUo = $codeUo;

        return $this;
    }

    /**
     * Get codeUo
     *
     * @return string 
     */
    public function getCodeUo()
    {
        return $this->codeUo;
    }
}
