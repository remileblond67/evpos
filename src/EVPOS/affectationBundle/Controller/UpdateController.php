<?php
namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UpdateController extends Controller
{
    public function updateAvancementAction($codeService) {
      $service = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Service')
          ->getServiceFiche($codeService)
      ;

      return $this->render('EVPOSaffectationBundle:Utilisateur:fiche_service.html.twig', array('service' => $service));
    }
}
