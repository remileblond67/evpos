<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UtilisateurController extends Controller
{
    public function listeUtilisateurAction()
    {
        $listeUtil = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getUtilisateurs()
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_util.html.twig', array('listeUtil' => $listeUtil));
    }

    public function ficheUtilisateurAction($matUtil) {
        $util = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getUtilisateur($matUtil)
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_utilisateur.html.twig', array('util' => $util));
    }

    public function listeDirectionAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServices()
        ;

        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_direction.html.twig', array('listeDirection' => $listeDirection));
    }

    public function ficheDirectionAction($codeDirection) {
        $direction = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirection($codeDirection)
        ;

        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_direction.html.twig', array('direction' => $direction));
    }

    public function listeServiceAction() {
        $listeService = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Service')
            ->getServices()
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_service.html.twig', array('listeService' => $listeService));
    }

    public function ficheServiceAction($codeService) {
        $service = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Service')
            ->getService($codeService)
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_service.html.twig', array('service' => $service));
    }

    public function listeEcAction()
    {
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_ec.html.twig');
    }
    
    /**
     * Mise à jour des informations provenant de BAZA (directions et services)
     */
    public function majBazaDirServAction() {
        $updateBaza = $this->container->get('evpos_affectation.update_baza');
        
        $updateBaza->importDirections();
        $updateBaza->importServices();
        
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_ec.html.twig');
    }
    
    /**
     * Mise à jour des informations provenant de BAZA (utilisateurs)
     */
    public function majBazaUtilAction() {
        $updateBaza = $this->container->get('evpos_affectation.update_baza');
        
        $updateBaza->importUtilisateurs();
        
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_ec.html.twig');
    }    
    
    /**
     * Import des accès depuis GAP
     */
    public function importAccesAction() {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        $updateGap->importAcces();
    }
    
    
}
