<?php

namespace EVPOS\affectationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UOType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeUo')
            ->add('nomUo')
            ->add('migMoca')
            ->add('ancienCitrix')
            ->add('avancementMoca')
            ->add('avancementMocaDetail')
            ->add('noteAvancementMoca')
            ->add('typePoste')
            ->add('existeSuapp')
            ->add('nbUtil')
            ->add('appli')
            ->add('listePostes')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EVPOS\affectationBundle\Entity\UO'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'evpos_affectationbundle_uo';
    }
}
