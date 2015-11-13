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
class ImportBazaSuappCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_baza_suapp')
            ->setDescription('Import des données BAZA et SUAPP')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln("Fin du traitement");
	}
}