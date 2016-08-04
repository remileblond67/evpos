<?php

namespace EVPOS\affectationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UtilisateurType extends AbstractType
{
  /**
   * @param FormBuilderInterface $builder
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
      $builder
          ->add('matUtil')
          ->add('nomUtil')
      ;
  }

  /**
   * @param OptionsResolverInterface $resolver
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver)
  {
      $resolver->setDefaults(array(
          'data_class' => 'EVPOS\affectationBundle\Entity\Utilisateur'
      ));
  }

  /**
   * @return string
   */
  public function getName()
  {
      return 'evpos_affectationbundle_utilisateur';
  }
}
