<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilUo;

/**
* Import des accès applicatifs à partir de la base BAZA
* - BAZA : accès aux UO
*/
class ImportAccesUoCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
    ->setName('evpos:import_acces_uo')
    ->setDescription('Import des accès aux UO depuis la base BAZA')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    ini_set('memory_limit', -1);
    gc_enable();

    $em = $this->getContainer()->get('doctrine')->getManager();
    $repUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur');
    $repUo = $em->getRepository('EVPOSaffectationBundle:UO');
    $repAccesUo = $em->getRepository('EVPOSaffectationBundle:AccesUtilUo');

    // Connexion à la base de données BAZA
    $user = $this->getContainer()->getParameter('oracle_user');
    $password = $this->getContainer()->getParameter('oracle_pwd');
    $sid = "pbaza";
    $this->ORA = oci_connect ($user , $password , $sid) ;
    if (! $this->ORA) {
      print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ;
      $output->writeln("Impossible de se connecter à la base de données");
    } else {
      // Suppression des anciens accès aux UO
      $output->write("Suppression des anciens accès aux UO... ");
      $listeAccesUo = $repAccesUo->findAll();
      foreach($listeAccesUo as $acces) {
        $em->remove($acces);
      }
      $em->flush();
      unset($listeAccesUo);
      $output->writeln("OK");

      gc_collect_cycles();

      // Récupération de la liste des utilisateurs connus
      $listeUtil = $repUtil->getUtilisateurs();

      $nbUtil = 0;

      $output->writeln("Import des accès aux UO à partir de BAZA");
      $nbUtil = 0;

      $requeteBaza = "SELECT distinct code_uo
      FROM (SELECT REGEXP_REPLACE (REGEXP_REPLACE (UPPER (ntmgname), '^GA_', ''), '_P$', '')
      CODE_UO
      FROM baz_member
      WHERE     UPPER (ntmgname) LIKE 'GA\_%\_P' ESCAPE '\'
      AND upper(ntmuid) = :matricule
      UNION
      SELECT REGEXP_REPLACE (REGEXP_REPLACE (nom_role_util, '^._[^_]+_'),
      '_.*')
      code_uo
      FROM baz_role_a_util
      WHERE upper(nom_util) = :matricule
      UNION
      SELECT a.id_module code_uo
      FROM app_role_appli a, baz_member m
      WHERE     a.code_env = 'PROD'
      AND UPPER (a.code_role_appli) = UPPER (m.ntmgname)
      AND upper(a.code_role_appli) not in ('GA_TSE', 'GA_ACINT_PROFIL1')
      AND m.ntmuid = :matricule)";
      $csr = oci_parse ( $this->ORA , $requeteBaza) ;

      foreach ($listeUtil as $utilisateur) {
        $matUtilisateur = $utilisateur->getMatUtil();

        // Récupération de la liste des accés de l'utilisateur dans GAP
        oci_bind_by_name($csr, ':matricule', $matUtilisateur);
        oci_execute ($csr) ;

        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
          $codeUo = $row["CODE_UO"] ;

          if ($repUo->isUo($codeUo)) {
            $uo = $repUo->getUo($codeUo);

            // Création de l'accès
            $newAcces = new AccesUtilUo();

            $newAcces->setUoAcces($uo);
            unset($uo);
            $newAcces->setUtilAcces($utilisateur);
            $newAcces->setSourceImport("Import BAZA du ".date("d/m/Y"));

            $em->persist($newAcces);
            unset($newAcces);
          }
        }
        $nbUtil++;
        if ($nbUtil%100 == 0) {
          $output->write($nbUtil." ");
        }
      }
      oci_free_statement($csr);
      $output->writeln("OK");
      $output->write("Validation en base...");
      $em->flush();
      $output->writeln("Fin de l'import");

      oci_close ($this->ORA) ;
    }

    $output->writeln("Fin du traitement");
  }
}
