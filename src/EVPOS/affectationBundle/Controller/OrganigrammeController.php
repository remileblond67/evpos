<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\Service;

class OrganigrammeController extends Controller
{
    // TreeView des services et direction, en fonction du nombre d'agents
    public function treeViewOrgaAction() {
      $em = $this->getDoctrine()->getManager();

      $listeDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->getDirections();
      $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->getServicesActifs();
      return $this->render('EVPOSaffectationBundle:Organigramme:treeview_service.html.twig', ['listeDirection' => $listeDirection, 'listeService' => $listeService]);
    }

    // TreeView des services et direction, en fonction du nombre d'agents et de l'état de planification
    public function treeViewOrgaPlanifAction() {
      $em = $this->getDoctrine()->getManager();

      $listeDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->getDirections();
      $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->getServicesActifs();
      return $this->render('EVPOSaffectationBundle:Organigramme:treeview_service_planif.html.twig', ['listeDirection' => $listeDirection, 'listeService' => $listeService]);
    }

    // Liste des secteurs
    public function listeSecteurAction() {
      $listeSecteur = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:Secteur')->findAll();
      return $this->render('EVPOSaffectationBundle:Organigramme:liste_secteur.html.twig', ['listeSecteur'=>$listeSecteur]);
    }

    // Fiche secteur
    public function ficheSecteurAction($codeSecteur) {
      $secteur = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:Secteur')->getSecteur($codeSecteur);
      if ($secteur !== NULL) {
        return $this->render('EVPOSaffectationBundle:Organigramme:fiche_secteur.html.twig', ['secteur'=>$secteur]);
      } else {
        $this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Impossible de trouver le secteur '".$codeSecteur."'"));
        return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
      }
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
     * Affiche la fiche d'une direction dont le code est passé en paramétre
     */
    public function ficheDirectionAction($codeDirection) {
        $direction = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getDirection($codeDirection)
        ;

        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_direction.html.twig', array('direction' => $direction));
    }
}
