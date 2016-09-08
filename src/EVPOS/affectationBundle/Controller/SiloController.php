<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EVPOS\affectationBundle\Entity\Silo;

class SiloController extends Controller
{
    /**
     * Liste des silos
     */
    public function listeSiloAction() {
      $listeSilo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Silo')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Silo:liste_silo.html.twig', array('listeSilo' => $listeSilo));
    }

    /**
     * Affichage de la fiche d'un silo
     */
    public function ficheSiloAction($idSilo) {
      $silo = $this->getDoctrine()->getManager()
                   ->getRepository('EVPOSaffectationBundle:Silo')
                   ->getSiloId($idSilo)
      ;

      return $this->render('EVPOSaffectationBundle:Silo:fiche_silo.html.twig', array('silo' => $silo));
    }

    /**
     * Affiche le tableau de répartition des UO sur les silos
     */
    public function tableauUoSiloAction() {
      $listeUo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getUoSilo()
      ;
      return $this->render('EVPOSaffectationBundle:Silo:tab_silo.html.twig', array('listeUo' => $listeUo));
    }
}
