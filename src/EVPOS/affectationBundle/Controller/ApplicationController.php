<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\UO;

class ApplicationController extends Controller
{
    /**
     * Cherche une application
     */
    public function chercheAppliAction() {
        $request = $this->get('request');
        $appli = new Application;

        $form = $this->createFormBuilder($appli)
            ->add('codeAppli')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirect($this->generateUrl('evpos_ficheAppli', array('codeAppli' => strtoupper(trim($form['codeAppli']->getData())))));
        }
        return $this->render('EVPOSaffectationBundle:Application:recherche_appli.html.twig', array('form' => $form->createView()));
    }

    /**
     * Cherche une UO
     */
    public function chercheUoAction() {
        $request = $this->get('request');
        $uo = new UO;

        $form = $this->createFormBuilder($uo)
            ->add('codeUo')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            return $this->redirect($this->generateUrl('evpos_ficheUo', array('codeUo' => strtoupper(trim($form['codeUo']->getData())))));
        }
        return $this->render('EVPOSaffectationBundle:Application:recherche_uo.html.twig', array('form' => $form->createView()));
    }

    /**
     * Liste des applications et UO dans un tableau HTML
     */
    public function listeAppliAction() {
        $listeAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationsUo()
        ;

        return $this->render('EVPOSaffectationBundle:Application:liste_appli.html.twig', array('listeAppli' => $listeAppli));
    }

    /**
     * Affichage de la fiche d'une UO dont le code est passé en paramètre
     */
    public function ficheUoAction($codeUo) {
        $uo = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:UO')
            ->getUoFull($codeUo)
        ;

        if ($uo == NULL) {
            $this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Impossible de trouver l'UO ".$codeUo));
            return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
        }
        else {
            return $this->render('EVPOSaffectationBundle:Application:fiche_uo.html.twig', array('uo' => $uo));
        }
    }

    /**
     * Affichage de la fiche d'une application dont le code est passé en paramètre
     */
    public function ficheAppliAction($codeAppli) {
        $appli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getApplicationFull($codeAppli)
        ;

        if ($appli == NULL) {
            $this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Impossible de trouver l'application ".$codeAppli));
            return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
        } else {
            return $this->render('EVPOSaffectationBundle:Application:fiche_appli.html.twig', array('appli' => $appli));
        }
    }

    /**
     * Liste des applications de chaque service
     */
    public function listeAppliServiceAction() {
        $listeDirServAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Direction')
            ->getListeDirServAppli()
        ;
        return $this->render('EVPOSaffectationBundle:Application:liste_appli_service.html.twig', array('listeDirServAppli' => $listeDirServAppli));
    }
}
