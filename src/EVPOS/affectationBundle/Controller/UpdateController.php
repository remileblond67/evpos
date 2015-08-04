<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UpdateController extends Controller
{
	/**
	 * Mise � jour de la liste des applications � partir de SUAPP
	 */
    public function majSuappAction(Request $request) {
        $updateSuapp = $this->container->get('evpos_affectation.update_suapp');

        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSuapp->importAppliSuapp()));
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSuapp->importUoSuapp()));
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateSuapp->importCpiSuapp()));
         
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }
    
    /**
     * Mise � jour des informations provenant de BAZA (directions et services)
     */
    public function majBazaDirServAction(Request $request) {
        $updateBaza = $this->container->get('evpos_affectation.update_baza');
        
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateBaza->importDirections()));
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateBaza->importServices()));
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }
    
    /**
     * Mise � jour des informations provenant de BAZA (utilisateurs)
     */
    public function majBazaUtilAction(Request $request) {
        $updateBaza = $this->container->get('evpos_affectation.update_baza');
        
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateBaza->importUtilisateurs()));
        
        return $this->redirect($this->generateUrl('evpos_indicateurs'));
    }    
    
    /**
     * Remont�e des acc�s utilisateurs au niveau d'un service
     */
    public function updateAccesServiceAction(Request $request, $codeService) {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateGap->updateAccesService($codeService)));
        
        return $this->redirect($this->generateUrl('evpos_ficheService', array('codeService' => $codeService)));
    }
    
    /**
     * Mise � jour des RIU de chaque service, � partir des informations contenues dans GAP
     */
    public function majRiuAction(Request $request) {
        $updateGap = $this->container->get('evpos_affectation.update_gap');
        $request->getSession()->getFlashBag()->add('info', utf8_encode($updateGap->updateRiu()));
        
        return $this->redirect($this->generateUrl('evpos_listeService'));
    }
    
}
