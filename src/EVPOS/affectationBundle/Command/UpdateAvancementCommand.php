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

        // Mise à jour de l'avancement de la migration des UO
        $output->write("Lecture du fichier d'avancement des FIA... ");
        $csvFile = fopen("/home/data/evpos/dev/avancement/MOCA_avancement.csv", "r");
        while (($data = fgetcsv($csvFile, 0, ';')) !== FALSE) {
          $codeUo = $data[0];
          $avancementDetail = $data[1];
          $avancement = $data[2];
          $uo = $em->getRepository('EVPOSaffectationBundle:UO')->getUo($codeUo);
          if ($uo !== NULL) {
            $uo->setAvancementMoca($avancement);
            $uo->setAvancementMocaDetail($avancementDetail);
            $em->persist($uo);
          }
        }
        fclose($csvFile);
        $em->flush();
        $output->writeln("OK");

        // Notes d'avancement des UO
        $output->write("Mise à jour de la note d'avancement des UO... ");

        $baremeNote = array(

          "06"=>30,
          "07"=>10,
          "08"=>80,
          "09"=>90,
          "10"=>80,
          "11"=>50,
          "12"=>90,
          "13"=>100,
          "14"=>100,
          "15"=>100,
          "00"=>100
        );
        $listeUo = $em->getRepository('EVPOSaffectationBundle:UO')->findAll();
        foreach($listeUo as $uo) {
            if (strlen($uo->getAvancementMocaDetail()) > 2) {
              $code = substr($uo->getAvancementMocaDetail(),0,2);
              switch ($code) {
                case "01":
                  $note = 5;
                  break;
                case "02":
                  $note = 10;
                  break;
                case "03":
                  $note = 20;
                  break;
                case "04":
                  $note = 10;
                  break;
                case "05":
                  $note = 30;
                  break;
                case "06":
                  $note = 30;
                  break;
                case "07":
                  $note = 10;
                  break;
                case "08":
                  $note = 80;
                  break;
                case "09":
                  $note = 90;
                  break;
                case "10":
                  $note = 80;
                  break;
                case "11":
                  $note = 50;
                  break;
                case "12":
                  $note = 90;
                  break;
                case "13":
                  $note = 100;
                  break;
                case "14":
                  $note = 100;
                  break;
                case "15":
                  $note = 100;
                  break;
                case "00":
                  $note = 100;
                  break;
                default:
                  $note = 0;
                  break;
              }
              if (($note != 100) && ($uo->getAncienCitrix() === TRUE)) {
                $note = 80;
              }
              $uo->setNoteAvancementMoca($note);
              $em->persist($uo);
            }
        }
        $em->flush();
        unset($listeUo);
        $output->writeln("OK");

        // Note d'avancement des applications
        $output->write("Mise à jour de la note d'avancement des applications... ");
        $listeAppli = $em->getRepository('EVPOSaffectationBundle:application')->findAll();
        foreach ($listeAppli as $appli) {
          $sommeNote = 0;
          $nbUtil = 0;
          foreach ($appli->getListeUO() as $uo) {
            if ($uo->getMigMoca()) {
              $nbUtilUo = $uo->getListeAcces()->count();
              $sommeNote += $uo->getNoteAvancementMoca() * $nbUtilUo;
              $nbUtil += $nbUtilUo ;
            }
            if ($nbUtil == 0) {
              $note = 100;
            } else {
              $note = $sommeNote / $nbUtil ;
            }
            $appli->setNoteAvancementMoca($note);
            $em->persist($appli);
          }
        }
        unset($listeAppli);
        $output->writeln("OK");

        // Note d'avancement des services
        $output->write("Mise à jour de la note d'avancement des services... ");
        $listeServices = $em->getRepository('EVPOSaffectationBundle:Service')->findAll();
        foreach ($listeServices as $service) {
            $sommeNote = 0;
            $nbUtil = 0;
            foreach ($service->getListeAccesUo() as $acces) {
                if ($acces->getUoAcces()->getMigMoca()) {
                    $sommeNote += $acces->getUoAcces()->getNoteAvancementMoca() * $acces->getNbUtil();
                    $nbUtil += $acces->getNbUtil();
                }
            }
            if ($nbUtil == 0) {
                $service->setNoteAvancementMoca(100);
            } else {
                $service->setNoteAvancementMoca(round($sommeNote / $nbUtil));
            }
            $em->persist($service);
        }
        unset($listeServices);
        $em->flush();
        $output->writeln("OK");


        // Note d'avancement des directions
        $output->write("Mise à jour de la note d'avancement des directions... ");
        $listeDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->findAll();
        foreach ($listeDirection as $direction) {
          $sommeNote = 0;
          $nbUtil = 0;
          foreach ($direction->getListeServices() as $service) {
            $nbUtil += $service->getNbAgent();
            $sommeNote += $service->getNoteAvancementMoca() * $service->getNbAgent();
          }
          if ($nbUtil == 0) {
            $direction->setNoteAvancementMoca(100);
          } else {
            $direction->setNoteAvancementMoca($sommeNote / $nbUtil);
          }
          $em->persist($direction);
        }
        unset($listeDirection);
        $em->flush();
        $output->writeln("OK");
    }
}
