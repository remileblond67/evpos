<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    /**
     * Liste des applications et UO au format XML, pour exploitation dans Excel
     */
    public function listeAppliXmlAction()
    {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsUo()
        ;

        return $this->render('EVPOSaffectationBundle:Application:liste_appli.xml.twig', array('listeAppli' => $listeAppli));
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
            ->getApplication($codeAppli)
        ;

        return $this->render('EVPOSaffectationBundle:Application:fiche_appli.html.twig', array('appli' => $appli));
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

        return $this->render('EVPOSaffectationBundle:Application:export_uo_nexthink.xml.twig', array('listeUo' => $listeUo));
    }

    /**
     * Export de la définition des différentes applications pour import dans Nexthink
     *
     */
    public function exportAppliNexthinkAction() {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsUo()
        ;

        return $this->render('EVPOSaffectationBundle:Application:export_appli_nexthink.xml.twig', array('listeAppli' => $listeAppli));
    }
	
	/**
	 * Mise à jour de la liste des applications à partir de SUAPP
	 */
	 public function majSuappAction() {
		$updateSuapp = $this->container->get('evpos_affectation.update_suapp');
        $message = $updateSuapp->importAppliSuapp() . $updateSuapp->importUoSuapp() ;
        
		return $this->render('EVPOSaffectationBundle:Application:update_suapp.html.twig', array('message' => $message)); 
	 }
}

