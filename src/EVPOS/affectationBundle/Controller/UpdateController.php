<?php
namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UpdateController extends Controller
{
    public function updateUpdateEnsembleServiceAction($codeService, Request $request) {
      $service = $this->getDoctrine()
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Service')
        ->getServiceFiche($codeService)
      ;

      $form = $this->createFormBuilder($service)
        ->setAction($this->generateUrl('evpos_update_ensemble_service'))
        ->setMethod('GET')
        ->add('codeService', 'text', array('read_only' => true))
        ->add('numEnsemble', 'integer')
        ->add('save', 'submit', array('label' => 'app.update'))
        ->getForm()
      ;

      if ($form->handleRequest($request)->isValid()) {
        return $this->redirectToRoute('evpos_ficheService', array('codeService' => $form['codeService']));
      }

      return $this->render('EVPOSaffectationBundle:Utilisateur:update_ensemble_service.html.twig', array(
        'form' => $form->createView(),
      ));
    }
}
