<?php
namespace EVPOS\affectationBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use EVPOS\affectationBundle\Entity\AccesUtilAppli;

/**
 * Import des accès applicatifs à partir de la base GAP
 * - GAP : accès aux applications
 */
class ImportAccesAppliCommand extends ContainerAwareCommand
{
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_acces_appli')
            ->setDescription('Import des accès aux applications depuis le base GAP')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
      gc_enable();

      $em = $this->getContainer()->get('doctrine')->getManager();
      $repUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur');
      $repAppli = $em->getRepository('EVPOSaffectationBundle:Application');
      $repAcces = $em->getRepository('EVPOSaffectationBundle:AccesUtilAppli');

      // Connexion à la base de données GAP
      $user = $this->getContainer()->getParameter('oracle_user');
  		$password = $this->getContainer()->getParameter('oracle_pwd');
  		$sid = "pgap";
      $this->ORA = oci_connect ($user , $password , $sid) ;
      if (! $this->ORA) {
		  print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ;
  		  $output->writeln("Impossible de se connecter à la base de données");
  		} else {
        // Suppression des anciens accès aux applications
        $output->write("Suppression des anciens accès aux applications... ");
        $listeAcces = $repAcces->getListeAccesAppli();
        foreach($listeAcces as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        unset($listeAcces);
        gc_collect_cycles();
        $output->writeln("OK");

        // Récupération de la liste des utilisateurs connus
        $listeUtil = $repUtil->getUtilisateurs();
        $nbUtil = 0;

        $output->writeln("Import des accès aux applications à partir de GAP");

        $requeteBaza = "select distinct code_application from gap_user_application where upper(matricule)=:matricule";
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;

        foreach ($listeUtil as $utilisateur) {
          $matUtilisateur = $utilisateur->getMatUtil();

          // Récupération de la liste des accés de l'utilisateur dans GAP
          oci_bind_by_name($csr, ':matricule', $matUtilisateur);
          oci_execute ($csr) ;

          while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeApplication = strtoupper($row["CODE_APPLICATION"]) ;

            if ($repAppli->isApplication($codeApplication)) {
              $application = $repAppli->getApplication($codeApplication);

              // Création de l'accés
              $newAcces = new AccesUtilAppli();
              $newAcces->setAppliAcces($application);
              unset($application);
              $newAcces->setUtilAcces($utilisateur);
              $newAcces->setSourceImport("Import GAP du ".date("d/m/Y"));
              $em->persist($newAcces);
              unset($newAcces);
            }
          }
          $nbUtil++;
          if ($nbUtil%500 == 0) {
              $output->write($nbUtil." ");
          }
        }
        oci_free_statement($csr);
        $output->writeln("OK");
        $output->write("Validation en base...");
        $em->flush();
        $output->writeln("OK");
        $output->writeln("Fin de l'import");

        oci_close ($this->ORA) ;
      }
      $output->writeln("Fin du traitement");
    }
}
