<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    // Appel de la page d'accueil
    public function indexAction() {
        return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
    }

    // Appel de la page des indicateurs
    public function indicateursAction() {
        // Récupération du nombre de directions
        $nbDirections = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getNbDirections()
        ;

        // Récupération du nombre de services
        $nbServices = $this->getDoctrine()
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

        // Récupération du nombre de postes
        $nbPoste = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
            ->getNbPoste()
        ;

        // Récupération du nombre de postes MOCA
        $nbPosteMoca = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
            ->getNbPosteMoca()
        ;

        // Récupération du nombre de postes à migrer dans MOCA
        $nbPosteAMigrer = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
            ->getNbPosteAMigrer()
        ;

        // Réparition des postes par type d'usage
        $nbPosteUsage = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
            ->getNbPosteUsage()
        ;

        // Répartition des postes par master
        $nbPosteMaster = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Poste')
          ->getNbPosteMaster()
        ;

        // Note d'avancement par nature d'Application
        // $avancementNature = $this->getDoctrine()
        //     ->getManager()
        //     ->getRepository('EVPOSaffectationBundle:Application')
        //     ->getAvancementNature()
        // ;
        $avancementNature = NULL;

        return $this->render('EVPOSaffectationBundle:Default:indicateurs.html.twig', array(
          'nbAppli' => $nbAppli,
          'nbUtil' => $nbUtil,
          'nbAccesUtilAppli' => $nbAccesUtilAppli,
          'nbService' => $nbServices,
          'nbDirection' => $nbDirections,
          'nbPoste' => $nbPoste,
          'nbPosteAMigrer' => $nbPosteAMigrer,
          'nbPosteMoca' => $nbPosteMoca,
          'nbPosteUsage' => $nbPosteUsage,
          'nbPosteMaster' => $nbPosteMaster,
          'avancementNature'=>$avancementNature)
        );
    }
}
