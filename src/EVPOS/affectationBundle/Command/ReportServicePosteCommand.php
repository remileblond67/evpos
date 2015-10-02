<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Poste;
use EVPOS\affectationBundle\Entity\Utilisateur;


class ReportServicePosteCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:report_util_poste')
            ->setDescription('Report des services utilisateurs sur les postes')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		$em = $this->getContainer()->get('doctrine')->getManager();
		
		$output->writeln("*** Report des services utilisateurs sur les postes  ***");
        $postesSansService = $em->getRepository('EVPOSaffectationBundle:Poste')->getPostesSansService();
        
        foreach($postesSansService as $poste) {
            foreach ($poste->getListeUtilisateurs() as $user) {
                $service = $user->getServiceUtil();
                $poste->setService($service);
                $output->write('_');
            }
            $em->persist($poste);
            $output->write('.');
        }
		$output->writeln("OK");
        unset($postesSansService);
        
        $output->writeln("Fin de report");
        $em->flush();
        $output->writeln("Validation des modifications");
	}
}