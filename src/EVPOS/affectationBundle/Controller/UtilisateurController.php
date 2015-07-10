<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UtilisateurController extends Controller
{
    /**
     * Affiche la liste de tous les utilisateurs
     * liste paginée
     */
    public function listeUtilisateurAction(Request $request)
    {
        // Récupération du numéro de page courante
        $page = $request->query->get('page');
        
        if ($page < 1) 
            $page = 1;
            
        $nbParPage = 100;
        
        $listeUtil = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getUtilisateursPage($page, $nbParPage)
        ;
        
        $nbPages = ceil(count($listeUtil)/$nbParPage);
        if ($nbPages != 0 and $page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_util.html.twig',
                             array('listeUtil' => $listeUtil,
                                   'nbPages' => $nbPages,
                                   'page' => $page));
    }

    /**
     * Affiche la fiche d'un utilisateur dont le matricule est passé en paramètre
     */
    public function ficheUtilisateurAction($matUtil) {
        $util = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Utilisateur')
            ->getUtilisateurFull($matUtil)
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_utilisateur.html.twig', array('util' => $util));
    }

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
    public function listeEcAction()
    {
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_ec.html.twig');
    }
    
    /**
     * Mise à jour des informations provenant de BAZA (directions et services)
     */
    public function majBazaDirServAction(Request $request) {
        $updateBaza = $this->container->get('evpos_affectation.update_baza');
        
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateBaza->importDirections()));
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateBaza->importServices()));
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }
    
    /**
     * Mise à jour des informations provenant de BAZA (utilisateurs)
     */
    public function majBazaUtilAction(Request $request) {
        $updateBaza = $this->container->get('evpos_affectation.update_baza');
        
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateBaza->importUtilisateurs()));
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }    
    
    /**
     * Import des accès depuis GAP
     */
    public function importAccesAction(Request $request) {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateGap->importAcces()));
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }
    
    public function updateAccesServiceAction(Request $request, $codeService) {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateGap->updateAccesService($codeService)));
        
        return $this->redirect($this->generateUrl('evpos_ficheService', array('codeService' => $codeService)));
    }
    
    
}
