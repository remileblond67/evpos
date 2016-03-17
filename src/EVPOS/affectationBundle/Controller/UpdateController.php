<?php
namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UpdateController extends Controller
{
    public function updateAvancementAction($codeService) {
      $service = $this->getDoctrine()
          ->getManager()
          ->getRepository('EVPOSaffectationBundle:Service')
          ->getServiceFiche($codeService)
      ;

      $form = $this->createFormBuilder($service)
        ->add('codeService', TextType::class)
        ->add('numEnsemble', IntType::class)
        ->add('save', SubmitType::class, array('label' => 'Mettre à jour'))
        ->getForm()
      ;

      if ($form->isValid()) {
        return $this->redirect($this->generateUrl('evpos_fiche_service', array('codeService' => $form['codeService'])));
      }

      return $this->render('EVPOSaffectationBundle:Utilisateur:update_ensemble_service.html.twig', array('form' => $form->createView()));
    }
}
