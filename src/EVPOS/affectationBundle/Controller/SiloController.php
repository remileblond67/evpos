<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
      return $this->render('EVPOSaffectationBundle:Silo:liste_silo.html.twig',
                            array('listeSilo' => $listeSilo));
    }

    /**
     * Tableau de répartition des UO dans les silos
     */
    public function tabSiloAction() {
      $tabSilo = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('EVPOSaffectationBundle:UO')
                 ->getUoDirection()
      ;

      $listeDir = $this->getDoctrine()
      ->getManager()
      ->getRepository('EVPOSaffectationBundle:Direction')
      ->getDirections()
      ;

      return $this->render('EVPOSaffectationBundle:Silo:liste_uo_direction.html.twig',
                            array('tabSilo' => $tabSilo, 'listeDir' => $listeDir));
    }

    /**
     * Export XML de la répartition des UO dans les silos
     */
    public function exportTabSiloAction() {
      $tabSilo = $this->getDoctrine()
                 ->getManager()
                 ->getRepository('EVPOSaffectationBundle:UO')
                 ->getUoDirection()
      ;
      return $this->render('EVPOSaffectationBundle:Silo:export_uo_dir.xml.twig',
                            array('tabSilo' => $tabSilo));
    }


    /**
     * Affichage de la fiche d'un silo
     */
    public function ficheSiloAction($idSilo) {
      $silo = $this->getDoctrine()->getManager()
                   ->getRepository('EVPOSaffectationBundle:Silo')
                   ->getSiloId($idSilo)
      ;

      return $this->render('EVPOSaffectationBundle:Silo:fiche_silo.html.twig',
                            array('silo' => $silo));
    }

    /**
     * Affiche le tableau de répartition des UO sur les silos
     */
    public function repSiloAction() {
      $listeSilo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Silo')
          ->listeSiloProd()
      ;

      $listeUo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getUoSilo()
      ;

      $listeUoMultiSilo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getUoPlusieursSilos()
      ;

      return $this->render('EVPOSaffectationBundle:Silo:rep_silo.html.twig',
                            array('listeUo' => $listeUo,
                           'listeSilo' => $listeSilo,
                           'listeUoMultiSilo' => $listeUoMultiSilo));
    }
}
