<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportLongController extends Controller {
  /**
   * Export XML des postes et de leurs équipements
   */
  public function exportDirServicePosteUtilXmlAction() {
      $listeDirection = $this->getDoctrine()->getManager()
          ->getRepository('EVPOSaffectationBundle:Direction')
          ->getListeDirServicePosteUtil()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_dir_serv_poste_equip_util.xml.twig', array('listeDirection' => $listeDirection));
  }

  /**
   * Export XML des postes
   */
  public function exportDirServicePosteSeulUtilXmlAction() {
      $listeDirection = $this->getDoctrine()->getManager()
          ->getRepository('EVPOSaffectationBundle:Direction')
          ->getListeDirServicePosteUtil()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_dir_serv_poste_util.xml.twig', array('listeDirection' => $listeDirection));
  }

  /**
   * Export des Services, utilisateurs et de leurs accès applicatifs
   */
  public function exportServiceUtilAppliXmlAction() {
      $listeDirServiceUtilAppli = $this->getDoctrine()->getManager()
          ->getRepository('EVPOSaffectationBundle:Direction')
          ->getListeDirServiceUtilAppli()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_service_util_appli.xml.twig', array('listeDirection' => $listeDirServiceUtilAppli));
  }
}
