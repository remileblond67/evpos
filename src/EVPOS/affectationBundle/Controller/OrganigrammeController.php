<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OrganigrammeController extends Controller
{
    /**
     * Affiche la liste des directions
     */
    public function listeDirectionAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServices()
        ;

        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_direction.html.twig', array('listeDirection' => $listeDirection));
    }

    /**
     * Affiche la fiche d'une direction dont le code est passé en paramètre
     */
    public function ficheDirectionAction($codeDirection) {
        $direction = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirection($codeDirection)
        ;

        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_direction.html.twig', array('direction' => $direction));
    }

    /**
     * Affiche la liste des services
     */
    public function listeServiceAction() {
        $listeService = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Service')
            ->getServicesFull()
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_service.html.twig', array('listeService' => $listeService));
    }

    /**
     * Affiche la fiche d'un service dont le code est passé en paramètre
     */
    public function ficheServiceAction($codeService) {
        $service = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Service')
            ->getService($codeService)
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_service.html.twig', array('service' => $service));
    }
    
    /**
     * Affiche la liste des ensembles cohérents
     */
    public function listeEcAction() {
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_ec.html.twig');
    }
}
