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

        // Récupération du nombre d'applications
        $nbUtil = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getNbUtilisateurs()
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

        // Récupération du nombre de postes sous Windows XP
        $nbPosteXp = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
            ->getNbPosteXp()
        ;

        // R�partition du nombre de poste XP par service
        $nbPosteXpService = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Poste')
          ->getNbPosteXpService()
        ;

        // R�partition du nombre de poste XP par master
        $nbPosteXpMaster = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Poste')
          ->getNbPosteXpMaster()
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

        // Répartition des postes par avancement de la migration
        $nbPosteAvancement = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Poste')
          ->getNbPosteAvancement()
        ;

        $avancementUoAI = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getAvancementGeneral('AI')
        ;

        $nbPipeAS = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getNbPipeUo("AS");
          
        $nbPipeAI = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getNbPipeUo("AI");

        $avancementUoAS = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getAvancementGeneral('AS')
        ;

        $avancementUoAIDetail = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getAvancementDetail('AI')
        ;

        $avancementUoASDetail = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getAvancementDetail('AS')
        ;

        $nbUoAS = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getNbUo('AS')
        ;

        $nbUoAI = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:UO')
          ->getNbUo('AI')
        ;

        $evolutionNbPoste = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoPoste')
        ->getHistoPoste()
        ;
        
        $evolutionNbPosteXp = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoPosteXp')
        ->getHistoPosteXp()
        ;

        $evolutionNbPosteSemaine = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoPoste')
        ->getHistoPosteSemaine()
        ;

        $evolutionAiGen = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoUo')
        ->getHisto("AI", "general")
        ;

        $evolutionAsGen = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoUo')
        ->getHisto("AS", "general")
        ;

        $evolutionAiDet = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoUo')
        ->getHisto("AI", "detail")
        ;

        $evolutionAsDet = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:HistoUo')
        ->getHisto("AS", "detail")
        ;


        return $this->render('EVPOSaffectationBundle:Default:indicateurs.html.twig', array(
          'nbUtil' => $nbUtil,
          'nbService' => $nbServices,
          'nbDirection' => $nbDirections,
          'nbPoste' => $nbPoste,
          'nbPosteXp' => $nbPosteXp,
          'nbPosteXpService' => $nbPosteXpService,
          'nbPosteXpMaster' => $nbPosteXpMaster,
          'nbPosteAMigrer' => $nbPosteAMigrer,
          'nbPosteMoca' => $nbPosteMoca,
          'nbPosteUsage' => $nbPosteUsage,
          'nbPosteMaster' => $nbPosteMaster,
          'nbPosteAvancement' => $nbPosteAvancement,
          'avancementUoAI' => $avancementUoAI,
          'avancementUoAS' => $avancementUoAS,
          'avancementUoAIDetail' => $avancementUoAIDetail,
          'avancementUoASDetail' => $avancementUoASDetail,
          'nbUoAI' => $nbUoAI,
          'nbUoAS' => $nbUoAS,
          'evolutionNbPoste' => $evolutionNbPoste,
          'evolutionNbPosteSemaine' => $evolutionNbPosteSemaine,
          'evolutionNbPosteXp' => $evolutionNbPosteXp,
          'evolutionAiGen' => $evolutionAiGen,
          'evolutionAsGen' => $evolutionAsGen,
          'evolutionAiDet' => $evolutionAiDet,
          'evolutionAsDet' => $evolutionAsDet,
          'nbPipeAI' => $nbPipeAI,
          'nbPipeAS' => $nbPipeAS,
        )
        );
    }
}
