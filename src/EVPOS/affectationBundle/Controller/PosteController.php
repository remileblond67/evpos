<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PosteController extends Controller
{
    /**
     * Liste des postes connus
     */
    public function listePosteAction() {
        $listePoste = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Poste')
            ->getPostes()
        ;
        
        return $this->render('EVPOSaffectationBundle:Utilisateur:liste_poste.html.twig', array('listePoste' => $listePoste));
    }
    
    /**
     * Import des postes depuis fichier CSV
     */
    public function importPosteAction() {


        return $this->redirect($this->generateUrl('evpos_listePoste'));
    }
}
