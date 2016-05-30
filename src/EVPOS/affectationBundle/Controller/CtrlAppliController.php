<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CtrlAppliController extends Controller
{
  /**
   * Liste des applications sans FIA
   */
  public function uoSansFiaAction() {
    $listeUO = $this->getDoctrine()
      ->getManager()
      ->getRepository('EVPOSaffectationBundle:UO')
      ->getSansFIA()
    ;

    return $this->render('EVPOSaffectationBundle:Ctrl:appli_sans_fia.html.twig', array('listeUO' => $listeUO));
  }

  /**
   * Liste des applications sans utilisateurs
   */
  public function uoSansUtilAction() {
    $listeUO = $this->getDoctrine()
      ->getManager()
      ->getRepository('EVPOSaffectationBundle:UO')
      ->getSansUtil()
    ;

    return $this->render('EVPOSaffectationBundle:Ctrl:appli_sans_util.html.twig', array('listeUO' => $listeUO));
  }
}
