<?php
namespace EVPOS\affectationBundle\Entity;
use EVPOS\affectationBundle\Entity\DataAvancement;
class DataAvancement
{
  private $data;
  public function __construct()
  {
    $this->data = "";
  }

  public function setData($data) {
    $this->data = $data;
  }
  public function getData() {
    return $this->data;
  }
}
