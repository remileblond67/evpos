<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateAvancementCommand extends ContainerAwareCommand
{
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:update_avancement')
            ->setDescription('Mise à jour avancement migration des UO')
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

        // Mise à jour de l'avancement de la migration des UO
        $output->write("Lecture du fichier d'avancement des FIA (".$env.")... ");

        $csvFile = fopen("/home/data/evpos/".$env."/avancement/moca_avancement.csv", "r");
        // Lecture de la ligne de titre
        $titre = fgets($csvFile);
        $output->writeln("- ligne de titre : " . $titre);
        while (($data = fgetcsv($csvFile, 0, ';', '"')) !== FALSE) {
          $codeUo = $data[0];
          $avancementDetail = $data[1];
          $avancement = $data[2];
          $uo = $em->getRepository('EVPOSaffectationBundle:UO')->getUo($codeUo);
          if ($uo !== NULL) {
            if ($uo->getMigMoca()) {
              $uo->setAvancementMoca($avancement);
              $uo->setAvancementMocaDetail($avancementDetail);
            } else {
              $uo->setAvancementMoca(NULL);
              $uo->setAvancementMocaDetail(NULL);
            }
            $em->persist($uo);
          }
        }
        fclose($csvFile);
        $em->flush();
        $output->writeln("OK");

        // Notes d'avancement des UO
        $output->write("Mise à jour de la note d'avancement des UO... ");

        $listeUo = $em->getRepository('EVPOSaffectationBundle:UO')->findAll();
        foreach($listeUo as $uo) {
          $uo->calculeNoteAvancement();
          $em->persist($uo);
        }
        unset($listeUo);
        $em->flush();
        $output->writeln("OK");

        // Note d'avancement des applications
        $output->write("Mise à jour de la note d'avancement des applications... ");
        $listeAppli = $em->getRepository('EVPOSaffectationBundle:application')->findAll();
        foreach ($listeAppli as $appli) {
          $appli->calculeNoteAvancement();
          $em->persist($appli);
        }
        unset($listeAppli);
        $output->writeln("OK");

        // Note d'avancement des services
        $output->write("Mise à jour de la note d'avancement des services... ");
        $listeServices = $em->getRepository('EVPOSaffectationBundle:Service')->findAll();
        foreach ($listeServices as $service) {
          $service->calculeNoteAvancement();
          $em->persist($service);
        }
        unset($listeServices);
        $em->flush();
        $output->writeln("OK");


        // Note d'avancement des directions
        $output->write("Mise à jour de la note d'avancement des directions... ");
        $listeDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->findAll();
        foreach ($listeDirection as $direction) {
          $direction->calculeNoteAvancement();
          $em->persist($direction);
        }
        unset($listeDirection);
        $em->flush();
        $output->writeln("OK");

        // Note d'avancement des secteurs
        $output->write("Mise à jour de la note d'avancement des secteurs... ");
        $listeSecteur = $em->getRepository('EVPOSaffectationBundle:Secteur')->findAll();
        foreach ($listeSecteur as $secteur) {
          $secteur->calculeNoteAvancement();
          $em->persist($secteur);
        }
        unset($listeSecteur);
        $em->flush();
        $output->writeln("OK");
    }
}
