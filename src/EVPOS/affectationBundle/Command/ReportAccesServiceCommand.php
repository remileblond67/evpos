<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;
use EVPOS\affectationBundle\Entity\AccesUtilUo;
use EVPOS\affectationBundle\Entity\AccesServiceUo;

class ReportAccesServiceCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:report_acces_service')
            ->setDescription('Report des accès appli et UO au niveau des services')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        ini_set('memory_limit', -1);
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $repAppli = $em->getRepository('EVPOSaffectationBundle:Application');
        $repUo = $em->getRepository('EVPOSaffectationBundle:UO');
        $repAcces = $em->getRepository('EVPOSaffectationBundle:AccesUtilAppli');
        $repAccesUo = $em->getRepository('EVPOSaffectationBundle:AccesUtilUo');
        
        // Mise à jour des accès applicatifs de l'ensemble des services
        $output->writeln("Report des accès appli et uo sur les services");
        
        $output->write("Suppression des accès appli et UO de tous les services...");
        $listeAcces = $em->getRepository('EVPOSaffectationBundle:AccesServiceAppli')->getListeAccesServiceAppli();
        foreach($listeAcces as $acces) {
            $em->remove($acces);
        }
        
		$listeAccesUo = $em->getRepository('EVPOSaffectationBundle:AccesServiceUo')->getListeAccesServiceUo();
        foreach($listeAccesUo as $acces) {
            $em->remove($acces);
        }
        unset($listeAcces);
		$em->flush();
        $output->writeln("OK");        
        
        $output->writeln("Mise à jour des accès applicatifs au niveau des services");
        $listeServices = $em->getRepository('EVPOSaffectationBundle:Service')->getServices();

        foreach($listeServices as $service) {
			$output->write('Service '.$service->getCodeService().' ');
            $listeUtilisateurs = $service->getListeUtilisateurs();
            // Liste pour mémoriser les applications déjà traitées
            $listeAppli = array();
			$listeUo = array();

            foreach ($listeUtilisateurs as $util) {
                foreach ($util->getListeAcces() as $acces) {
                    $listeAppli[] = $acces->getAppliAcces()->getCodeAppli();
                }
				
				foreach ($util->getListeAccesUo() as $acces) {
					$listeUo[] = $acces->getUoAcces()->getCodeUo();
				}
            }
            $listeAppli = array_unique($listeAppli);
			$listeUo = array_unique($listeUo);
			
            unset($listeUtilisateurs);

            foreach ($listeAppli as $codeAppli) {
                $newAcces = new AccesServiceAppli();
                
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
                
                $newAcces->setServiceAcces($service);
                $newAcces->setAppliAcces($appli);
                $newAcces->setSourceImport("Report service appli");

                $em->persist($newAcces);
            }
            unset($listeAppli);
			
			foreach ($listeUo as $codeUo) {
				$newAcces = new AccesServiceUo();
				
				$uo = $em->getRepository('EVPOSaffectationBundle:UO')->getUo($codeUo);
				
				$newAcces->setServiceAcces($service);
                $newAcces->setUoAcces($uo);
                $newAcces->setSourceImport("Report service UO");

                $em->persist($newAcces);
			}
			unset($listeUo);
            
            $em->flush();
        }
        unset($listeServices);
        
        $output->writeln("Fin d'import");
        $em->flush();
        $output->writeln("Import validé");
    }
}