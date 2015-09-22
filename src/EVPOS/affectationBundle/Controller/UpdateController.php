<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\DataAvancement;

class UpdateController extends Controller
{
	/**
	 * Mise à jour de la liste des applications à partir de SUAPP
	 */
    public function majSuappAction(Request $request) {
        $updateSuapp = $this->container->get('evpos_affectation.update_suapp');

        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSuapp->importAppliSuapp()));
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSuapp->importUoSuapp()));
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSuapp->importCpiSuapp()));
         
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
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
     * Remontée des accès utilisateurs au niveau d'un service
     */
    public function updateAccesServiceAction(Request $request, $codeService) {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateGap->updateAccesService($codeService)));
        
        return $this->redirect($this->generateUrl('evpos_ficheService', array('codeService' => $codeService)));
    }
    
    /**
     * Mise à jour des RIU de chaque service, à partir des informations contenues dans GAP
     */
    public function majRiuAction(Request $request) {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateGap->updateRiu()));
        
        return $this->redirect($this->generateUrl('evpos_listeService'));
    }
    
    /**
     * Mise à jour de l'avancement à partir de sharecan
     */
    public function updateShareCanAction(Request $request) {
        $request->getSession()->getFlashBag()->add('info', 'Fonction de mise à jour pas encore disponible');
        $updateSharecan = $this->container->get('evpos_affectation.update_sharecan');
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSharecan->updateAvancement()));
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }
	
	/** 
	 * Mise à jour de l'état d'intégration des UO
	 */
    
	public function updateAvancementUoAction() {
		$request = $this->get('request');
		$data = new DataAvancement();
        $formBuilder = $this->createFormBuilder($data);
        $formBuilder->add('data', 'textarea');
        $form = $formBuilder->getForm();
        
		return $this->render('EVPOSaffectationBundle:Application:update_avancement.html.twig', array(
            'form' => $form->createView(),
        ));
	}
    
    /**
     * Purge des données de la base
     */
    public function purgeBaseAction(Request $request) {
        $request->getSession()->getFlashBag()->add('info', 'Base de données purgée des éléments non utilisés');
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }
    
}
