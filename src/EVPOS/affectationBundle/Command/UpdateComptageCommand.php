<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class UpdateComptageCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
    ->setName('evpos:update_comptage')
    ->setDescription('Mise à jour du comptage des utilisateurs (UO)')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $output->writeln("*** Mise à jour des comptages utilisateurs sur les UO ***");

    $em = $this->getContainer()->get('doctrine')->getManager();
    $listeUO = $em->getRepository('EVPOSaffectationBundle:UO')->findAll();
    foreach ($listeUO as $uo) {
      $uo->setNbUtil(count($uo->getListeAcces()));
      $em->persist($uo);
    }
    $em->flush();
  }
}
