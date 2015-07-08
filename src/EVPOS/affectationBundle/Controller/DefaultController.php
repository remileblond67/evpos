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
        $nbAccesAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:AccesAppli')
            ->getNbAccesAppli()
        ;
        
        return $this->render('EVPOSaffectationBundle:Default:indicateurs.html.twig', array('nbAppli' => $nbAppli, 'nbUtil' => $nbUtil, 'nbAccesAppli' => $nbAccesAppli));
    }
}
