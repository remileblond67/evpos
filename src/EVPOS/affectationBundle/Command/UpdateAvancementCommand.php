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

        // Mise à jours des notes d'avancement des UO
        $output->write("Mise à jour de la note d'avancement des UO... ");
        $listeUo = $em->getRepository('EVPOSaffectationBundle:UO')->findAll();
        foreach($listeUo as $uo) {
            $note = 0;
            switch (substr($uo->getAvancementMoca(),0,1)) {
                case "3":
                    // Application validée
                    $note=100;
                    break;
                case "2":
                    // Intégration en cours
                    $note=50;
                    break;
                case "1":
                    // FIA initiée
                    $note=10;
                    break;
                default:
                    // Tout est à faire...
                    $note=0;
            }
            if (($note != 100) && ($uo->getAncienCitrix() === TRUE)) {
              $note = 80;
            }
            $uo->setNoteAvancementMoca($note);
        }
        $em->flush();
        unset($listeUo);
        $output->writeln("OK");

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
        $em->flush();
        $output->writeln("OK");
    }
}
