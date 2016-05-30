<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Poste;
use EVPOS\affectationBundle\Entity\Imprimante;

/**
* Import des imprimantes et des accès depuis les postes
* - source : champ "cuscomputerlogonprinters" d'ActiveDirectory
*/
class ImportImprimantesCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
    ->setName('evpos:import_imprimantes')
    ->setDescription('Import des imprimantes')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $em = $this->getContainer()->get('doctrine')->getManager();
    $user = $this->getContainer()->getParameter('oracle_user');;
    $password = $this->getContainer()->getParameter('oracle_pwd');;

    $ad = ldap_connect('ldap://clinf004.cus.fr');
    ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
    ldap_set_option($ad, LDAP_OPT_REFERRALS, 0);
    $dn = 'OU=MasterXP,OU=XP,OU=Stations,DC=cus,DC=fr';
    $user = $this->getContainer()->getParameter('oracle_user');
    $password = $this->getContainer()->getParameter('oracle_pwd');
    @ldap_bind($ad, $user."@cus", $password);
    $attributes=array('dnshostname', 'cuscomputerlogonprinters');

    $sr = ldap_search($ad, $dn, "(&(objectclass=computer)(cuscomputerlogonprinters=*))", $attributes);
    $listePostes = ldap_get_entries($ad, $sr);
    $output->writeln($listePostes["count"]." postes à traiter...");

    $output->writeln("Import des imprimantes sur les postes...");
    for ($i=0; $i<$listePostes["count"]; $i++) {
      $hostname = str_replace(".cus.fr", "", $listePostes[$i]["dnshostname"][0]);
      $poste = $em->getRepository('EVPOSaffectationBundle:Poste')->getPoste($hostname);
      if ($poste !== NULL) {
        // Suppression de la liste des imprimantes
        $poste->delListeImprimante();

        $nbPrinter = $listePostes[$i]["cuscomputerlogonprinters"]["count"];
        for ($j=0; $j<$nbPrinter; $j++) {
          $hostnameImprimante = $listePostes[$i]["cuscomputerlogonprinters"][$j];
          $imprimante = $em->getRepository('EVPOSaffectationBundle:imprimante')->getImprimante($hostnameImprimante);
          if ($imprimante === NULL) {
            // Création de l'imprimante
            $imprimante = new imprimante();
            $imprimante->setHostname($hostnameImprimante);
            $em->persist($imprimante);
            $em->flush();
          }
          $poste->addListeImprimante($imprimante);
          $em->persist($poste);
        }
        $output->write('.');
      } else {
        $output->write('?');
      }
    }
    $output->write("\nValidation en base...");
    $em->flush();
    $output->writeln("OK");

    $output->write("Report sur les utilisateurs...");
    $listeUtilisateurs = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->findAll();
    foreach ($listeUtilisateurs as $util) {
      $util->reportImprimantes();
      $em->persist($util);
    }
    $em->flush();
    $output->writeln("OK");
  }
}
