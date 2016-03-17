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

      $form = $this->createFormBuilder($service)
        ->add('codeService')
        ->add('numEnsemble')
        ->add('save', submit, array('label' => 'Mettre à jour'))
        ->getForm()
      ;

      return $this->render('EVPOSaffectationBundle:Utilisateur:update_ensemble_service.html.twig', array(
        'form' => $form->createView(),
      ));
    }
}
