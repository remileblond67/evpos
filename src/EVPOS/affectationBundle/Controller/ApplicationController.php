<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ApplicationController extends Controller
{
    /**
     * Liste des applications et UO dans un tableau HTML
     */
    public function listeAppliAction()
    {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsUo()
        ;

        return $this->render('EVPOSaffectationBundle:Application:liste_appli.html.twig', array('listeAppli' => $listeAppli));
    }

    public function listeUoAction()
    {
        return $this->render('EVPOSaffectationBundle:Application:liste_uo.html.twig');
    }

    /**
     * Affichage de la fiche d'une UO dont le code est passé en paramètre
     */
    public function ficheUoAction($codeUo) {
        $uo = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:UO')
            ->getUo($codeUo)
        ;
        return $this->render('EVPOSaffectationBundle:Application:fiche_uo.html.twig', array('uo' => $uo));
    }

    /**
     * Affichage de la fiche d'une application dont le code est passé en paramètre
     */
    public function ficheAppliAction($codeAppli) {
        $appli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationFull($codeAppli)
        ;

        return $this->render('EVPOSaffectationBundle:Application:fiche_appli.html.twig', array('appli' => $appli));
    }
	        
    /**
     * Liste des applications de chaque service
     */
    public function listeAppliServiceAction() {
        $listeDirServAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDirServAppli()
        ;
        return $this->render('EVPOSaffectationBundle:Application:liste_appli_service.html.twig', array('listeDirServAppli' => $listeDirServAppli));
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
        return $this->render('EVPOSaffectationBundle:Application:liste_appli_service.xml.twig', array('listeDirServAppli' => $listeDirServAppli));
    }
}

