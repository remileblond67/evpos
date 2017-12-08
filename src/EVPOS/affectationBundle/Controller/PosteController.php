<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\Poste;


class PosteController extends Controller
{

	/**
	* Cherche un poste à partir de son hostname
	*/
	public function cherchePosteAction() {
		$request = $this->get('request');
		$poste = new Poste;

		$form = $this->createFormBuilder($poste)
		->add('hostname')
		->getForm();

		$form->handleRequest($request);

		if ($form->isValid()) {
			return $this->redirect($this->generateUrl('evpos_fichePoste', array('hostname' => strtoupper(trim($form['hostname']->getData())))));
		}
		return $this->render('EVPOSaffectationBundle:Poste:recherche_poste.html.twig', array('form' => $form->createView()));
	}

	/**
	* Affichage de la fiche d'un poste
	*/
	public function fichePosteAction($hostname) {
		$poste = $this->getDoctrine()
		->getManager()
		->getRepository('EVPOSaffectationBundle:Poste')
		->getPoste($hostname)
		;

		if ($poste === NULL) {
			$this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Impossible de trouver le poste ".$hostname));
			return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
		}
		else {
			return $this->render('EVPOSaffectationBundle:Poste:fiche_poste.html.twig', array('poste' => $poste));
		}
	}

	/**
	* Liste des postes connus
	*/
	public function listePosteAction(Request $request) {
		// Récupération du numéro de page courante
		$page = $request->query->get('page');

		if ($page < 1)
		$page = 1;

		$nbParPage = 1000;

		$listePoste = $this->getDoctrine()
		->getManager()
		->getRepository('EVPOSaffectationBundle:Poste')
		->getPostesPages($page, $nbParPage)
		;

		$nbPages = ceil(count($listePoste)/$nbParPage);
		if ($nbPages != 0 && $page > $nbPages) {
			throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		}
		return $this->render('EVPOSaffectationBundle:Poste:liste_poste.html.twig',
		array('listePoste' => $listePoste,
		'nbPages' => $nbPages,
		'page' => $page));
	}

	/**
	 * Liste des postes à reprendre
	 */
	public function listePosteAReprendreAction() {
		$listePoste = $this->getDoctrine()
			->getManager()
			->getRepository('EVPOSaffectationBundle:Poste')
			->listePosteAReprendre()
		;

		return $this->render('EVPOSaffectationBundle:Poste:liste_poste_a_reprendre.html.twig',
			array('listePoste' => $listePoste))
		;
	}
	
	/**
	 * Liste des postes sous Windows XP
	 */
	public function listePosteXpAction() {
		$listePoste = $this->getDoctrine()
			->getManager()
			->getRepository('EVPOSaffectationBundle:Poste')
			->listePosteXp()
		;

		return $this->render('EVPOSaffectationBundle:Poste:liste_poste_xp.html.twig',
			array('listePoste' => $listePoste))
		;
	}
	
	/**
	 * Liste des postes sous Windows XP, par service
	 */
	public function listePosteXpServiceAction() {
		$listeService = $this->getDoctrine()
			->getManager()
			->getRepository('EVPOSaffectationBundle:Poste')
			->getNbPosteXpService()
		;

		return $this->render('EVPOSaffectationBundle:Poste:liste_poste_xp_service.html.twig',
			array('listeService' => $listeService))
		;
	}
}
