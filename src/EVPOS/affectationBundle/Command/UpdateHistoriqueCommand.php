<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\HistoPoste;
use EVPOS\affectationBundle\Entity\HistoPosteXp;
use EVPOS\affectationBundle\Entity\HistoUo;

/**
* Import des postes à partir de GPARC
*/
class UpdateHistoriqueCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
    ->setName('evpos:update_historique')
    ->setDescription('Mise à jour des indicateurs historiques')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $em = $this->getContainer()->get('doctrine')->getManager();
    $output->writeln("*** Mise à jour des indicateurs historiques ***");

    $output->write("Historisation du nombre de PC MOCA/à migrer... ");
    $histoPoste = new HistoPoste;

    // Récupération du nombre de postes MOCA
    $nbPosteMoca = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Poste')
        ->getNbPosteMoca()
    ;
    $histoPoste->setNbPosteMoca($nbPosteMoca);

    // Récupération du nombre de postes à migrer dans MOCA
    $nbPosteAMigrer = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Poste')
        ->getNbPosteAMigrer()
    ;
    $histoPoste->setNbPosteTodo($nbPosteAMigrer);

    $histoPoste->setDateMesure(new \DateTime());
    $em->persist($histoPoste);
    $em->flush();
    $output->writeln("OK");
    
    // M�morisation du nombre de poste XP restants
    $output->write("Historisation du nombre de PC XP restant... ");
    $histoPosteXp = new HistoPosteXp;
    $histoPosteXp->setDateMesure(new \DateTime());
    
    // Postes XP
    $nbPosteXp = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Poste')
        ->getNbPosteXp()
    ;
    $histoPosteXp->setNbPosteXp($nbPosteXp);

    // Postes XP hors reseau
    $nbPosteXpHr = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Poste')
        ->getNbPosteXpHr()
    ;
    $histoPosteXp->setNbPosteXpHr($nbPosteXpHr);

    // Postes bornes sous XP
    $nbPosteBorne = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:Poste')
        ->getNbPosteBorne()
    ;
    $histoPosteXp->setNbPosteBorne($nbPosteBorne);


    $em->persist($histoPosteXp);
    $em->flush();
    $output->writeln("OK");

    foreach (["AS", "AI"] as $nature) {
      $output->write("Historisation des états des UO ".$nature."... ");
      $avancementUoGeneral = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:UO')
        ->getAvancementGeneral($nature)
      ;
      foreach ($avancementUoGeneral as $ligne) {
        $histoUo = new histoUo;
        $histoUo->setNatureAppli($nature);
        $histoUo->setAvancement($ligne["avancementMoca"]);
        $histoUo->setNbUo($ligne["nb"]);
        $histoUo->setNiveau("general");
        $histoUo->setDateMesure(new \DateTime());
        $em->persist($histoUo);
      }
      $avancementUoDetail = $this->getContainer()->get('doctrine')
        ->getManager()
        ->getRepository('EVPOSaffectationBundle:UO')
        ->getAvancementDetail($nature)
      ;
      foreach ($avancementUoDetail as $ligne) {
        $histoUo = new histoUo;
        $histoUo->setNatureAppli($nature);
        $histoUo->setAvancement($ligne["avancementMocaDetail"]);
        $histoUo->setNbUo($ligne["nb"]);
        $histoUo->setNiveau("detail");
        $histoUo->setDateMesure(new \DateTime());
        $em->persist($histoUo);
      }
      $em->flush();
      $output->writeln("OK");
    }
  }
}
