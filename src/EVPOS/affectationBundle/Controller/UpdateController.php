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
        ->add('numEnsemble', 'integer')
        ->add('save', 'submit', array('label' => 'app.update'))
        ->getForm()
      ;

      if ($form->isValid()) {
        return $this->redirectToRoute('evpos_ficheService', array('codeService' => $form['codeService']));
      }

      return $this->render('EVPOSaffectationBundle:Utilisateur:update_ensemble_service.html.twig', array(
        'form' => $form->createView(),
      ));
    }
}
