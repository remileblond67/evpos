<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportController extends Controller
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

    #                               --- EXPORTS XML ---

    /**
     * Export XML des services
     */
    public function exportXmlServiceAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServices()
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_service.xml.twig', array('listeDirection' => $listeDirection));
    }

    /**
     * Export XML des services, avec la liste des RIU
     */
    public function exportXmlServiceRiuAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServices()
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_service_riu.xml.twig', array('listeDirection' => $listeDirection));
    }


    /**
     * Liste des applications et UO à migrer dans MOCA au format XML, pour exploitation dans Excel
     */
    public function listeAppliUoXmlAction() {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsFull()
        ;

        return $this->render('EVPOSaffectationBundle:Export:liste_appli.xml.twig', array('listeAppli' => $listeAppli));
    }

    /**
     * Liste de toutes les applications et UO au format XML, pour exploitation dans Excel
     */
    public function listeAppliUoAllXmlAction() {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsFull()
        ;

        return $this->render('EVPOSaffectationBundle:Export:liste_appli_all.xml.twig', array('listeAppli' => $listeAppli));
    }

    /**
     * Liste des applications à migrer dans MOCA au format XML, pour exploitation dans Excel
     */
    public function listeAppliXmlAction() {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsUo()
        ;

        return $this->render('EVPOSaffectationBundle:Export:liste_appli_only.xml.twig', array('listeAppli' => $listeAppli));
    }

    /**
     * Export de la définition des différents UO pour import dans Nexthink
     *
     */
    public function exportUoNexthinkAction() {
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
    public function exportAppliNexthinkAction() {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsFull()
        ;

        return $this->render('EVPOSaffectationBundle:Export:export_appli_nexthink.xml.twig', array('listeAppli' => $listeAppli));
    }

    /**
     * Liste des applications de chaque service au format XML
     */
    public function listeAppliServiceXmlAction() {
        $listeDirServAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDirServAppli()
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_appli_service.xml.twig', array('listeDirServAppli' => $listeDirServAppli));
    }

    /**
     * Export des Services et utilisateurs
     */
    public function exportServiceUtilXmlAction() {
        $listeDirServiceUtil = $this->getDoctrine()->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDirServiceUtil()
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_service_util.xml.twig', array('listeDirection' => $listeDirServiceUtil));
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

    /**
     * Export des Services, utilisateurs et de leurs accès UO
     */
    public function exportServiceUtilUoXmlAction() {
        $listeDirServiceUtilAppli = $this->getDoctrine()->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDirServiceUtilAppli()
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_service_util_uo.xml.twig', array('listeDirection' => $listeDirServiceUtilAppli));
    }

    /**
     * Export d'un service, avec l'ensemble de ses utilisateurs et de leurs accès UO
     */
    public function exportServiceUtilUoXmlServiceAction($codeService) {
        $listeDirServiceUtilAppli = $this->getDoctrine()->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDir1ServiceUtilAppli($codeService)
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_service_util_uo.xml.twig', array('listeDirection' => $listeDirServiceUtilAppli));
    }

    /**
     * Export d'un utilisateur en export 33
     */
    public function exportServiceUtilUoXmlUtilAction($matUtil) {
        $listeDirServiceUtilAppli = $this->getDoctrine()->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDir1UtilUtilAppli($matUtil)
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_service_util_uo.xml.twig', array('listeDirection' => $listeDirServiceUtilAppli));
    }

    /**
     * Export XML des postes et de leurs équipements
     */
    public function exportDirServicePosteUtilXmlAction() {
        ini_set('memory_limit', -1);
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
        ini_set('memory_limit', -1);
        $listeDirection = $this->getDoctrine()->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDirServicePosteUtil()
        ;
        return $this->render('EVPOSaffectationBundle:Export:liste_dir_serv_poste_util.xml.twig', array('listeDirection' => $listeDirection));
    }
}
