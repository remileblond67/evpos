<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeUsageExclus
 *
 * @ORM\Table("evpos_type_usage_exclus")
 * @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\TypeUsageExclusRepository")
 */
class TypeUsageExclus
{
    /**
     * @var string
     *
     * @ORM\Column(name="typeUsage", type="string", length=255)
     * @ORM\Id
     */
    private $typeUsage;

    /**
     * Set typeUsage
     *
     * @param string $typeUsage
     * @return TypeUsageExclus
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
}
