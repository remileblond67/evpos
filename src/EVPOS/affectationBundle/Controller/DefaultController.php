<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
    }

    public function indicateursAction()
    {
        // Récupération du nombre de services
        $nbService = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Service')
            ->getNbServices()
        ;
        
        // Récupération du nombre d'applications par nature
        $nbAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getNbAppliNat()
        ;
        
        // Récupération du nombre d'applications
        $nbUtil = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getNbUtilisateurs()
        ;
        
        // Récupération du nombre d'accès GAP
        $nbAccesUtilAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:AccesUtilAppli')
            ->getNbAccesUtilAppli()
        ;
        
        return $this->render('EVPOSaffectationBundle:Default:indicateurs.html.twig', array('nbAppli' => $nbAppli, 'nbUtil' => $nbUtil, 'nbAccesUtilAppli' => $nbAccesUtilAppli, 'nbService' => $nbService));
    }
}
