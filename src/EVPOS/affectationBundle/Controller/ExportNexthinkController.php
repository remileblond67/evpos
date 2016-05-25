<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportNexthinkController extends Controller {
  /**
   * Export de la définition des différents UO pour import dans Nexthink
   *
   */
  public function exportUoAction() {
      $listeUo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getListeUo()
      ;

      return $this->render('EVPOSaffectationBundle:Export:export_uo_nexthink.xml.twig', array('listeUo' => $listeUo));
  }

  /**
   * Export de la définition des différentes applications pour import dans Nexthink
   *
   */
  public function exportAppliAction() {
      $listeAppli = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Application')
          ->getApplicationsFull()
      ;

      return $this->render('EVPOSaffectationBundle:Export:export_appli_nexthink.xml.twig', array('listeAppli' => $listeAppli));
  }
}
