<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PosteController extends Controller
{
	/**
	 * Affichage de la fiche d'un poste
	 */
	public function fichePosteAction($hostname) {
		$poste = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
			->getPoste($hostname)
		;
		
		if ($poste == NULL) {
            $this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Impossible de trouver le poste ".$poste));
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
     * Import des postes depuis fichier CSV
     */
    public function importPosteAction() {
        return $this->redirect($this->generateUrl('evpos_listePoste'));
    }
}
