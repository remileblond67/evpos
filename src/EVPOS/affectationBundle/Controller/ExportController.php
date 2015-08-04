<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExportController extends Controller
{
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
     * Liste des applications et UO au format XML, pour exploitation dans Excel
     */
    public function listeAppliXmlAction()
    {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsFull()
        ;

        return $this->render('EVPOSaffectationBundle:Export:liste_appli.xml.twig', array('listeAppli' => $listeAppli));
    }
    
    /**
     * Liste des applications au format XML, pour exploitation dans Excel
     */
    public function listeAppliXmlOnlyAction()
    {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplications()
        ;

        return $this->render('EVPOSaffectationBundle:Export:liste_appli_only.xml.twig', array('listeAppli' => $listeAppli));
    }    
    
    /**
     * Export de la liste des CPI pour diffusion de message
     */
    public function exportCpiAction() {
        $listeCpi = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getListeCpi()
        ;
        
        return $this->render('EVPOSaffectationBundle:Export:export_cpi.html.twig', array('listeCpi' => $listeCpi));
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

}