<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExportController extends Controller
{
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
}
