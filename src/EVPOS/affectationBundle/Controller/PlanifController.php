<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\UO;

class PlanifController extends Controller
{
  /**
   * Affiche un calendrier des migrations d'ensembles cohérents
   */
   public function calendrierEnsembleAction() {

     return $this->render('EVPOSaffectationBundle:Planif:calendrier_ensemble.html.twig');
   }
}
