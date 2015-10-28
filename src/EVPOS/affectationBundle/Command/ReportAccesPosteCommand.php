<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilUo;
use EVPOS\affectationBundle\Entity\Uo;


class ReportAccesPosteCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:report_acces_poste')
            ->setDescription('Report des accès postes au niveau des utilisateurs')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        ini_set('memory_limit', -1);
		$em = $this->getContainer()->get('doctrine')->getManager();
		
		$output->writeln("*** Report des accès postes sur les utilisateurs ***");
        $listePosteUtilisateursAppli = $em->getRepository('EVPOSaffectationBundle:Poste')->getPosteUtilisateursAppli();
        
        foreach($listePosteUtilisateursAppli as $poste) {
            foreach ($poste->getListeUtilisateurs() as $util) {
                foreach ($poste->getListeUo() as $uo) {
					$acces = $em->getRepository('EVPOSaffectationBundle:AccesUtilUo')->getAccesUtilUo($util, $uo);
					
                    if ($acces === null) {
						$acces = new AccesUtilUo();
						$acces->setUtilAcces($util);
						$acces->setUoAcces($uo);
						$acces->setSourceImport("Report depuis accès poste");
						$em->persist($acces);
						$em->flush();
						$output->write("a");
                    } else {
						$acces->setSourceImport("Report depuis accès poste");
						$em->persist($acces);
						$em->flush();
						$output->write("m");
					}
                }
            }
        }
		$output->writeln("OK");
        unset($listePosteUtilisateursAppli);
        
        $output->writeln("Fin de report");
        $em->flush();
        $output->writeln("Validation des modifications");
	}
}
