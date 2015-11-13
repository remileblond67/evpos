<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;

/**
 * Import des acc�s applicatifs � partir de la base GAP 
 * - GAP : acc�s aux applications
 */
class ImportBazaSuappCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_baza_suapp')
            ->setDescription('Import des donn�es BAZA et SUAPP')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln("Fin du traitement");
	}
}