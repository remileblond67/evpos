<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use EVPOS\affectationBundle\Entity\Utilisateur;

class UtilisateurController extends Controller
{
  /**
  * Cherche un Utilisateur à partir de son matricule
  */
  public function chercheUtilisateurAction() {
    $request = $this->get('request');
    $util = new Utilisateur;

    $form = $this->createFormBuilder($util)
    ->add('matUtil')
    ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
      $util = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Utilisateur')
        ->getUtilisateurFull(strtoupper(trim($form['matUtil']->getData())))
      ;
      return $this->redirect($this->generateUrl('evpos_ficheUtil', array('matUtil' => $util->getMatUtil())));
    }
    return $this->render('EVPOSaffectationBundle:Utilisateur:recherche_utilisateur.html.twig', array('form' => $form->createView()));
  }

  /**
  * Cherche un Utilisateur à partir de son nom
  */
  public function chercheUtilisateurNomAction() {
    $request = $this->get('request');
    $util = new Utilisateur;

    $form = $this->createFormBuilder($util)
    ->add('nomUtil')
    ->getForm();

    $form->handleRequest($request);

    if ($form->isValid()) {
      return $this->redirect($this->generateUrl('evpos_ficheUtil', array('nomUtil' => strtoupper(trim($form['nomUtil']->getData())))));
    }
    return $this->render('EVPOSaffectationBundle:Utilisateur:recherche_utilisateur_nom.html.twig', array('form' => $form->createView()));
  }

  /**
  * Affiche la liste de tous les utilisateurs
  * liste paginée
  */
  public function listeUtilisateurAction(Request $request) {
    // Récupération du numéro de page courante
    $page = $request->query->get('page');

    if ($page < 1)
    $page = 1;

    $nbParPage = 1000;

    $listeUtil = $this->getDoctrine()
    ->getManager()
    ->getRepository('EVPOSaffectationBundle:Utilisateur')
    ->getUtilisateursPage($page, $nbParPage)
    ;

    $nbPages = ceil(count($listeUtil)/$nbParPage);
    if ($nbPages != 0 && $page > $nbPages) {
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

    if ($util !== NULL) {
      return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_utilisateur.html.twig', array('util' => $util));
    } else {
      $this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Impossible de trouver l'utilisateur ".$matUtil));
      return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
    }
  }

  /**
  * Extraction de la liste des CPI pour diffusion de message
  */
  public function listeCpiAction() {
    $listeCpi = $this->getDoctrine()
    ->getManager()
    ->getRepository('EVPOSaffectationBundle:Utilisateur')
    ->getListeCpi()
    ;

    return $this->render('EVPOSaffectationBundle:Utilisateur:liste_cpi.html.twig', array('listeCpi' => $listeCpi));
  }

}
