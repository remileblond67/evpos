<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CorrespUo
 *
 * @ORM\Table("evpos_corresp_uo")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\CorrespUoRepository")
 */
class CorrespUo
{
    /**
	 * Ancien code UO (à remplacer par le nouveau)
	 * -------------------------------------------
     * @var string
     *
     * @ORM\Column(name="old_code_uo", type="string", length=12)
     * @ORM\Id
     */
    private $oldCodeUo;

    /**
	 * Nouvelle UO (remplace l'ancien code)
	 * ----------------------------------------
    * @ORM\ManyToOne(targetEntity="EVPOS\affectationBundle\Entity\UO")
    * @ORM\JoinColumn(name="new_code_uo", referencedColumnName="code_uo", nullable=false)
    * @ORM\Id
    */
	private $newUo;

    /**
     * Set codeUo
     *
     * @param string $codeUo
     * @return CorrespUo
     */
    public function setOldCodeUo($oldCodeUo)
    {
        $this->oldCodeUo = $oldCodeUo;

        return $this;
    }

    /**
     * Get codeUo
     *
     * @return string 
     */
    public function getOldCodeUo()
    {
        return $this->oldCodeUo;
    }

    /**
     * Set newUo
     *
     * @param \EVPOS\affectationBundle\Entity\UO $newUo
     * @return CorrespUo
     */
    public function setNewUo(\EVPOS\affectationBundle\Entity\UO $newUo)
    {
        $this->newUo = $newUo;

        return $this;
    }

    /**
     * Get newUo
     *
     * @return \EVPOS\affectationBundle\Entity\UO 
     */
    public function getNewUo()
    {
        return $this->newUo;
    }
}
