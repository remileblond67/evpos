<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\Service;

class ServiceController extends Controller {
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
          ->getServiceFiche($codeService)
      ;
      return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_service.html.twig', array('service' => $service));
  }

}