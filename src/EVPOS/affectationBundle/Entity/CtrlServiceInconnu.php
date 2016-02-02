<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* CtrlServiceInconnu
*
* @ORM\Table("evpos_ctrl_service_inconnu")
* @ORM\Entity(repositoryClass="EVPOS\affectationBundle\Entity\CtrlServiceInconnuRepository")
*/
class CtrlServiceInconnu
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
  * @ORM\Column(name="codeSirh", type="string", length=4, nullable=true)
  */
  private $codeSirh;

  /**
  * @var string
  *
  * @ORM\Column(name="codeService", type="string", length=5, nullable=true)
  */
  private $codeService;

  /**
  * @var string
  *
  * @ORM\Column(name="remarque", type="string", length=255, nullable=true)
  */
  private $remarque;

  /**
  * Set codeService
  *
  * @param string $codeService
  * @return CtrlServiceInconnu
  */
  public function setCodeService($codeService)
  {
    $this->codeService = $codeService;

    return $this;
  }

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
  * Get codeService
  *
  * @return string
  */
  public function getCodeService()
  {
    return $this->codeService;
  }

  /**
  * Set codeSirh
  *
  * @param string $codeSirh
  * @return CtrlServiceInconnu
  */
  public function setCodeSirh($codeSirh)
  {
    $this->codeSirh = $codeSirh;

    return $this;
  }

  /**
  * Get codeSirh
  *
  * @return string
  */
  public function getCodeSirh()
  {
    return $this->codeSirh;
  }

  /**
  * Set remarque
  *
  * @param string $remarque
  * @return CtrlServiceInconnu
  */
  public function setRemarque($remarque)
  {
    $this->remarque = $remarque;

    return $this;
  }

  /**
  * Get remarque
  *
  * @return string
  */
  public function getRemarque()
  {
    return $this->remarque;
  }
}
