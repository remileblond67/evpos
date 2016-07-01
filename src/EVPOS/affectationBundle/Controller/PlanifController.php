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
     $services = $this->getDoctrine()->getManager()
       ->getRepository('EVPOSaffectationBundle:Service')
       ->getPlanif();

     $ensembles = $this->getDoctrine()->getManager()
       ->getRepository('EVPOSaffectationBundle:Service')
       ->getEnsembles();

     return $this->render('EVPOSaffectationBundle:Planif:calendrier_ensemble.html.twig',
                          array('services' => $services,
                                'ensembles' => $ensembles));
   }

   /**
    * Affiche un calendrier des imgrations d'applications
    */
   public function calendrierUoAction() {
     $ensembles = $this->getDoctrine()->getManager()
       ->getRepository('EVPOSaffectationBundle:Service')
       ->getEnsembles();

     $listeUo = $this->getDoctrine()->getManager()
       ->getRepository('EVPOSaffectationBundle:UO')
       ->getPlanifUo();

     return $this->render('EVPOSaffectationBundle:Planif:calendrier_uo.html.twig',
                          array('listeUo' => $listeUo,
                                'ensembles' => $ensembles));
   }
}
