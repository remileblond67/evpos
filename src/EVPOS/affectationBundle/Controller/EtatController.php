<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EtatController extends Controller
{
    /**
     * Affiche l'état d'avancement des services
     */
    public function etatAvancementServiceAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServicesAvancement()
        ;

        return $this->render('EVPOSaffectationBundle:Etat:avancementService.html.twig', array('listeDirection' => $listeDirection));
    }

    /**
     * Affiche l'état d'avancement des services
     *      - export XML -
     */
    public function etatAvancementServiceXmlAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServicesAvancement()
        ;

        return $this->render('EVPOSaffectationBundle:Etat:avancementService.xml.twig', array('listeDirection' => $listeDirection));
    }
}
