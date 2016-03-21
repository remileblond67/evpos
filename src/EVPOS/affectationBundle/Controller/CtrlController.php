<?php

namespace EVPOS\affectationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use EVPOS\affectationBundle\Entity\CorrespUo;

class CtrlController extends Controller
{
  /**
  * Affiche la liste des postes inconnus
  */
  public function listePosteInconnuAction() {
    $listePosteInconnu = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:CtrlPosteInconnu')->getUniques();
    return $this->render('EVPOSaffectationBundle:Ctrl:liste_poste_inconnu.html.twig', array('listePosteInconnu' => $listePosteInconnu));
  }

  /**
  * Affiche la liste des UO inconnues
  */
  public function listeUoInconnueAction() {
    $listeUoInconnue = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:CtrlUoInconnue')->getUniques();
    return $this->render('EVPOSaffectationBundle:Ctrl:liste_uo_inconnue.html.twig', array('listeUoInconnue' => $listeUoInconnue));
  }

  /**
  * Affiche les correspondances de code UO
  */
  public function listeCorrespUoAction() {
    $listeCorresp = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:CorrespUo')->findAll();
    return $this->render('EVPOSaffectationBundle:Ctrl:liste_corresp_uo.html.twig', array('listeCorresp' => $listeCorresp));
  }

  /**
  * Ajout d'une nouvelle correspondances de code UO
  */
  public function addCorrespUoAction(Request $request) {
    $em = $this->getDoctrine()->getManager();
    $correspUo = new correspUo();

    $form = $this->createFormBuilder($correspUo)
    ->add('oldCodeUo')
    ->add('newUo', 'entity', array(
      'class'    => 'EVPOSaffectationBundle:UO',
      'property' => 'codeUo',
      'multiple' => false ))
    ->add('save', 'submit', array('label' => 'app.add'))
    ->getForm()
    ;

    if ($form->handleRequest($request)->isValid()) {
      // $correspUo->setOldCodeUo($form['oldCodeUo']->getData());
      // $em->persist($correspUo);
      // $em->flush();
      return $this->redirectToRoute('evpos_ctrl_corresp_uo');
    }

    return $this->render('EVPOSaffectationBundle:Ctrl:form_corresp_uo.html.twig', array(
      'form' => $form->createView()
    ));
  }

  /**
  * Affiche la liste des utilisateurs GPARC non trouvés dans BAZA
  */
  public function listeUtilisateurInconnuAction() {
    $listeErreur = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:CtrlUtilisateurInconnu')->listeUtil();
    return $this->render('EVPOSaffectationBundle:Ctrl:liste_utilisateur_inconnu.html.twig', array('listeErreur' => $listeErreur));
  }

  /**
  * Affiche la liste des services inconnus
  */
  public function listeServiceInconnuAction() {
    $listeErreur = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:CtrlServiceInconnu')->findAll();
    return $this->render('EVPOSaffectationBundle:Ctrl:liste_service_inconnu.html.twig', array('listeErreur' => $listeErreur));
  }

  /**
  * Affiche la liste des utilisateurs qui ne se sont pas connectés depuis longtemps (6 mois)
  */
  public function listeAbsentServiceAction() {
    $listeAbsent = $this->getDoctrine()->getManager()->getRepository('EVPOSaffectationBundle:Utilisateur')->findAbsent();
    return $this->render('EVPOSaffectationBundle:Ctrl:liste_absent.html.twig', array('listeAbsent' => $listeAbsent));
  }
}
