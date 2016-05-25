<?php
namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\CorrespUo;

class UpdateController extends Controller
{
    public function updateServiceAction($codeService, Request $request) {
      $em = $this->getDoctrine()->getManager();
      $service = $em->getRepository('EVPOSaffectationBundle:Service')->getServiceFiche($codeService) ;

      $form = $this->createFormBuilder($service)
        ->add('codeService', 'text', array('read_only' => true))
        ->add('numEnsemble', 'integer', array('required' => false))
        ->add('save', 'submit', array('label' => 'app.update'))
        ->getForm()
      ;

      if ($form->handleRequest($request)->isValid()) {
        $service->setNumEnsemble($form['numEnsemble']->getData());
        $em->persist($service);
        $em->flush();
        return $this->redirectToRoute('evpos_ficheService', array('codeService' => $codeService));
      }

      return $this->render('EVPOSaffectationBundle:Utilisateur:update_service.html.twig', array(
        'form' => $form->createView(),
      ));
    }

    public function deleteCorrespUoAction($oldCodeUo, $newCodeUo) {
      $em = $this->getDoctrine()->getManager();
      $fb = $this->get("session")->getFlashBag();

      $correspUo = $em->getRepository('EVPOSaffectationBundle:CorrespUo')->getCorrespUo($oldCodeUo, $newCodeUo);

      if ($correspUo === NULL) {
        $fb->add('erreur', "Impossible de trouver l'Association");
      } else {
        $em->remove($correspUo);
        $em->flush();
        $fb->add('info', 'Association '.$oldCodeUo.'->'.$newCodeUo.' supprimée');
      }

      return $this->redirectToRoute('evpos_ctrl_corresp_uo');
    }

    public function addCorrespUoAction(Request $request) {
      $em = $this->getDoctrine()->getManager();
      $correspUo = new CorrespUo();
    }
}
