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

    $output->write("Marquage des silos existants...");
    $listeSilo = $em->getRepository("EVPOSaffectationBundle:Silo")->findAll();
    foreach ($listeSilo as $silo) {
      $silo->setExiste(false);
      foreach ($silo->getListeUO() as $uo) {
        $silo->removeListeUO($uo);
        $uo->removeListeSilo($silo);
        $em->persist($uo);
      }
      $em->persist($silo);
    }
    $em->flush();
    $output->writeln("OK");

    $fileName = "/home/data/evpos/".$env."/silo/update_appv.xml";
    try {
      $xml = simplexml_load_file($fileName);
    } catch(Exception $e) {
      $output->writeln("Impossible d'exploiter le fichier $fileName : ",  $e->getMessage(), "\n");
    }

    $output->write ("Création des silos Citrix... ");
    $output->write ("appli ");
    foreach ($xml->ListeSilos->Silos_Applicatif->silo as $nomSilo) {
      $silo = $em->getRepository("EVPOSaffectationBundle:Silo")->getSilo((string)$nomSilo);
      if ($silo === NULL) {
        $silo = new Silo;
      }
      $silo->setNomSilo((string)$nomSilo);
      $silo->setExiste(TRUE);
      $silo->setTypeSilo("appli");
      $em->persist($silo);
      $em->flush();
    }
    $output->write ("bureau ");
    foreach ($xml->ListeSilos->Silos_Bureau->silo as $nomSilo) {
      $silo = $em->getRepository("EVPOSaffectationBundle:Silo")->getSilo((string)$nomSilo);
      if ($silo === NULL) {
        $silo = new Silo;
      }
      $silo->setNomSilo((string)$nomSilo);
      $silo->setExiste(TRUE);
      $silo->setTypeSilo("bureau");
      $em->persist($silo);
      $em->flush();
    }
    $output->writeln("OK");

    $output->writeln ("Mise à jour de l'affectation des applications... ");
    $listeSiloUo = [];
    foreach ($xml->Applis->Appli as $app) {
      $codeUO = split('_',$app['nom'])[0];
      foreach ($app->silo as $nomSilo) {
        $listeSiloUo[$codeUO][] = (string)$nomSilo;
      }
    }

    foreach (array_keys($listeSiloUo) as $codeUO) {
      $uo = $em->getRepository("EVPOSaffectationBundle:UO")->getUO($codeUO);
      if ($uo !== NULL) {
        foreach (array_unique($listeSiloUo[$codeUO]) as $nomSilo) {
          $silo = $em->getRepository("EVPOSaffectationBundle:Silo")->getSilo((string)$nomSilo);
          if ($silo !== NULL) {
            $uo->addListeSilo($silo);
          } else {
            $output->writeln("Impossible de trouver le silo " . $nomSilo);
          }
        }
        $em->persist($uo);
      }
    }
    $em->flush();
    $output->writeln("OK");
  }
}
