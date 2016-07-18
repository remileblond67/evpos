<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EVPOS\affectationBundle\Entity\Silo;

class SiloController extends Controller
{
    /**
     * Liste des silos
     */
    public function listeSilo() {
      $listeSilo = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Silo')
          ->findAll()
      ;
      return $this->render('EVPOSaffectationBundle:Silo:liste_silo.html.twig', array('listeSilo' => $listeSilo));
    }
}
