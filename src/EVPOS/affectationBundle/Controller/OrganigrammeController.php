<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\Service;

class OrganigrammeController extends Controller
{
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

    // Cherche un service par son code
    public function chercheServiceAction() {
      $request = $this->get('request');
      $service = new Service;

      $form = $this->createFormBuilder($service)
        ->add('codeService')
        ->getForm();

      $form->handleRequest($request);

      if ($form->isValid()) {
        return $this->redirect($this->generateUrl('evpos_ficheService', array('codeService'=>strtoupper(trim($form['codeService']->getData())))));
      }
      return $this->render('EVPOSaffectationBundle:Utilisateur:recherche_service.html.twig', array('form' => $form->createView()));
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
     * Affiche la fiche d'un service dont le code est pass� en param�tre
     */
    public function ficheServiceAction($codeService) {
        $service = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Service')
            ->getService($codeService)
        ;
        return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_service.html.twig', array('service' => $service));
    }
}
