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
        ->add('codeService', 'text', array('read_only' => true))
        ->add('numEnsemble')
        ->add('save', 'submit', array('label' => 'Mettre à jour'))
        ->getForm()
      ;

      if ($form->isSubmitted()) {
        return $this->redirectToRoute('evpos_ficheService', array('codeService' => $form['codeService']));
      }

      return $this->render('EVPOSaffectationBundle:Utilisateur:update_ensemble_service.html.twig', array(
        'form' => $form->createView(),
      ));
    }
}
