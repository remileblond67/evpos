<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Silo;

/**
* Import des silos et de l'affectation des UO dans ces derniers
*/
class UpdateSiloAppliCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
      ->setName('evpos:update_silo_appli')
      ->setDescription("Mise à jour des silos et de l'affectation des UO dans ces derniers")
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $em = $this->getContainer()->get('doctrine')->getManager();

    // Identification de l'environnement courant
    if (strpos(getcwd(), "prod") !== false) {
      $env = "prod";
    } else {
      $env = "dev";
    }

    $output->writeln("Mise à jour des silos et de l''affectation des UO dans ces derniers (".$env.")");

    $output->write("Suppression des silos existants...");
    $listeSilos = $em->getRepository("EVPOSaffectationBundle:Silo")->findAll();
    foreach ($listeSilos as $silo) {
      $em->remove($silo);
    }
    $em->flush();
    $output->writeln("OK");

    $fileName = "/home/data/evpos/".$env."/silo/Update_AppV.xml";
    try {
      $xml = simplexml_load_file($fileName);
    } catch(Exception $e) {
      $output->writeln("Impossible d'exploiter le fichier $fileName : ",  $e->getMessage(), "\n");
    }

    $output->writeln ("Création des silos Citrix...");
    foreach ($xml->ListeSilos->Silos_Applicatif->silo as $nomSilo) {
      $output->writeln ("- " . $nomSilo . "\n");
      $newSilo = new Silo;
      //$newSilo->setNomSilo($nomSilo);
      $output->writeln($newSilo->getId());
      $em->persist($newSilo);
      $em->flush();
    }
    $output->writeln("OK");

    $output->writeln ("Mise à jour de l'affectation des applications...");
    foreach ($xml->Applis->Appli as $app) {
      $output->writeln("- " . $app['nom'] . " -> " . split('_',$app['nom'])[0]);
     foreach ($app->silo as $silo) {
       $output->writeln("  dispo dans le silo " . $silo);
     }
    }

    $output->writeln("OK");
  }
}
