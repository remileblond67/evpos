<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EtatController extends Controller
{
    /**
     * Affiche l'état d'avancement des services
     */
    public function avancementServiceAction() {
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
    public function avancementServiceXmlAction() {
        $listeDirection = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirectionsServicesAvancement()
        ;

        return $this->render('EVPOSaffectationBundle:Etat:avancementService.xml.twig', array('listeDirection' => $listeDirection));
    }

    /**
     * Etat de planification des services utilisant les différentes UO
     */
    public function planifUoAction() {
      listeUo = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:UO')
        ->getPlanifUo()
      ;

      return $this->render('EVPOSaffectationBundle:Etat:planif_uo.html.twig', array('listeUo' => $listeUo));
    }
}
