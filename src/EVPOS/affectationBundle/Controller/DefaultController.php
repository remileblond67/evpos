<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EVPOSaffectationBundle:Default:index.html.twig');
    }

    public function indicateursAction()
    {
        $nbAppli = $this->getDoctrine()
            ->getManager()
            ->getRepository('EVPOSaffectationBundle:Application')
            ->getNbAppliNat()
        ;
        return $this->render('EVPOSaffectationBundle:Default:indicateurs.html.twig', array('nbAppli' => $nbAppli));
    }
}
