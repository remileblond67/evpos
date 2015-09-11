<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;
use EVPOS\affectationBundle\Entity\AccesUtilUo;
use EVPOS\affectationBundle\Entity\AccesServiceUo;

class ReportServiceCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:report_service')
            ->setDescription('Report des accès appli et UO au niveau des services')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $repAppli = $em->getRepository('EVPOSaffectationBundle:Application');
        $repUo = $em->getRepository('EVPOSaffectationBundle:UO');
        $repAcces = $em->getRepository('EVPOSaffectationBundle:AccesUtilAppli');
        $repAccesUo = $em->getRepository('EVPOSaffectationBundle:AccesUtilUo');
        
        // Mise à jour des accès applicatifs de l'ensemble des services
        $output->writeln("Report des accès sur les services");
        
        $output->write("Suppression des accès appli de tous les services...");
        $listeAcces = $em->getRepository('EVPOSaffectationBundle:AccesServiceAppli')->getListeAccesServiceAppli();
        foreach($listeAcces as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        $output->writeln("OK");        
        
        $output->writeln("Mise à jour des accès service");
        $listeServices = $em->getRepository('EVPOSaffectationBundle:Service')->getServices();

        foreach($listeServices as $service) {
            $listeUtilisateurs = $service->getListeUtilisateurs();
            // Liste pour mémoriser les applications déjà traitées
            $listeAppli = array();

            foreach ($listeUtilisateurs as $util) {
                foreach ($util->getListeAcces() as $acces) {
                    $listeAppli[] = $acces->getAppliAcces()->getCodeAppli();
                }
            }
            $listeAppli = array_unique($listeAppli);

            foreach ($listeAppli as $codeAppli) {
                $newAcces = new AccesServiceAppli();
                
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
                
                $newAcces->setServiceAcces($service);
                $newAcces->setAppliAcces($appli);
                $newAcces->setSourceImport("NEW");

                $em->persist($newAcces);
            }
            
            $em->flush();
        }
        $output->writeln("Fin d'import");
        $em->flush();
        $output->writeln("Import validé");
    }
}
