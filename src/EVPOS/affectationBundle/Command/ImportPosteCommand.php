<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Poste;

/**
 * Import des postes à partir de GPARC
 */
class ImportPosteCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_poste')
            ->setDescription('Import des postes depuis la base GPARC')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		//pour se connecter en remplaçant par les bonnes valeurs, si tu ne les a pas je te les envoi dans un autre mail 
		$bdd_gparc = mssql_connect( "pgparc", "" , "");

		//place la requete ici
		$req = "SELECT NETWORK_IDENTIFIER FROM EVO_DATA50004.[50004].AM_ASSET WHERE NETWORK_IDENTIFIER LIKE ‘HMIC%’ AND NETWORK_IDENTIFIER LIKE ‘HPOR%’ ";

		$parse = oci_parse($bdd_gparc, $req);
		oci_execute($parse);
			
		while ($res = oci_fetch_assoc($parse)) {
						//affiche les champs que tu veux consulter
						$output->writeln($res["NETWORK_IDENTIFIER"]);
		}

		$output->writeln("Fin du traitement");
	}
}
