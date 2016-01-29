<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
* Import des RIU à partir de GAP
*/
class ImportRiuCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
    ->setName('evpos:import_riu')
    ->setDescription('Import des RIU depuis la base GAP')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $em = $this->getContainer()->get('doctrine')->getManager();

    $output->writeln("*** Import des RIU ***");
    $nbRiu = 0;
    $rUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur');
    $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->getServices();

    // Connexion à la base de données GAP
    $user = $this->getContainer()->getParameter('oracle_user');
    $password = $this->getContainer()->getParameter('oracle_pwd');
    $sid = "pgap";
    $ora = oci_connect ($user , $password , $sid) ;

    // Recherche, dans GAP, la liste des RIU du service
    $requeteGap = "select matricule from GAP_NT_RIU where code_service = :codeService";
    $csr = oci_parse ( $ora , $requeteGap) ;
    foreach ($listeService as $service) {
        // Suppression des anciens RIU
        foreach($service->getListeRiu() as $riu) {
            $service->removeListeRiu($riu);
        }
        $codeService = $service->getCodeService();
        oci_bind_by_name($csr, ':codeService', $codeService);
        oci_execute ($csr) ;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $matricule = $row["MATRICULE"] ;
            // Recherche de l'utilisateur correspondant au matricule
            if ($rUtil->isUtilisateur($matricule)) {
                $utilisateur = $rUtil->getUtilisateur($matricule);
                // Enregistrement des RIU dans le service
                $service->addListeRiu($utilisateur);
            }
        }
        $nbRiu++;
    }
    oci_close ($ora) ;
    $output->writeln("Fin du traitement (mise à jour de ".$nbRiu." RIU)");
  }
}
