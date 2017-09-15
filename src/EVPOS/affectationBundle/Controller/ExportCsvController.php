<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportCsvController extends Controller
{
    #                               --- EXPORTS CSV ---

    /**
     * Export CSV des files d'imprimantes
     */
    public function exportCsvUtilImprimanteAction() {
      $listeUtilisateur = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Utilisateur')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:export_printer.csv.twig', array('listeUtilisateur' => $listeUtilisateur));
    }

    /**
     * Export CSV des files d'imprimantes pour DEGIM (par service)
     */
    public function exportCsvUtilImprimanteDegimAction($codeService) {
      $listeUtilisateur = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Utilisateur')
          ->getUtilisateursService($codeService)
      ;
      return $this->render('EVPOSaffectationBundle:Export:export_printer.csv.twig', array('listeUtilisateur' => $listeUtilisateur));
    }

    /**
     * Export CSV des directions
     */
    public function exportCsvDirectionAction() {
      $listeDirection = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Direction')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_direction.csv.twig', array('listeDirection' => $listeDirection));
    }

    /**
     * Export CSV des services
     */
    public function exportCsvServiceAction() {
      $listeService = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Service')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_service.csv.twig', array('listeService' => $listeService));
    }

    /**
     * Export CSV des utilisateurs
     */
    public function exportCsvUtilisateurAction() {
      $listeUtilisateur = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Utilisateur')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_utilisateur.csv.twig', array('listeUtilisateur' => $listeUtilisateur));
    }

    /**
     * Export CSV des postes
     */
    public function exportCsvPosteAction() {
      $listePoste = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Poste')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_poste.csv.twig', array('listePoste' => $listePoste));
    }

    /**
     * Export CSV des affectations de postes
     */
    public function exportCsvUtilPosteAction() {
      $listePoste = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Poste')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_util_poste.csv.twig', array('listePoste' => $listePoste));
    }

   /**
     * Export CSV des applications
     */
    public function exportCsvApplicationAction() {
      $listeApplication = $this->getDoctrine()
           ->getManager()
           ->getRepository('EVPOSaffectationBundle:Application')
           ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_application.csv.twig', array('listeApplication' => $listeApplication));
    }

  /**
     * Export CSV des silos
     */
    public function exportCsvSiloAction() {
      $listeSilo = $this->getDoctrine()
           ->getManager()
           ->getRepository('EVPOSaffectationBundle:Silo')
           ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Export:liste_silo.csv.twig', array('listeSilo' => $listeSilo));
    }

    /**
     * Export CSV des UO des silos
     */
    public function exportCsvUoSiloAction() {
      $listeUoSilo = $this->getDoctrine()
           ->getManager()
           ->getRepository('EVPOSaffectationBundle:Silo')
           ->listeUoSilo()
      ;

      return $this->render('EVPOSaffectationBundle:Export:liste_uo_silo.csv.twig', array('listeUoSilo' => $listeUoSilo));
    }
}
