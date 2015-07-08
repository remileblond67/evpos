<?php

namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesAppli;

class ImportGapCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_gap')
            ->setDescription('Import des accès applicatifs depuis la base GAP')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pgap";
		
        $this->ORA = oci_connect ($user , $password , $sid) ;
        if (! $this->ORA) {
		  print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ; 
		  exit () ; 
		}
        
        // $name = $input->getArgument('name');
        $requeteBaza = "select distinct matricule, code_application from gap_user_application";
        $output->writeln($requeteBaza);
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $matUtilisateur = $row["MATRICULE"] ;
            $codeApplication = $row["CODE_APPLICATION"] ;
            
            if ($em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtilisateur) &&
                    $em->getRepository('EVPOSaffectationBundle:Application')->isApplication($codeApplication)) {
                $application = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeApplication);
                $utilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtilisateur);
                
                // Création de l'accès uniquement s'il n'existe pas préalablement
                if ($em->getRepository('EVPOSaffectationBundle:AccesAppli')->isAccesAppli($application, $utilisateur) == false) {
                    $newAcces = new AccesAppli();
                
                    $newAcces->setAppliAcces($application);
                    $newAcces->setUtilAcces($utilisateur);
                    $newAcces->setSourceImport("Import GAP du ".date("d.m.y"));
                    
                    $em->persist($newAcces);

                    if ($nb%1000 == 0) {
                        $output->writeln($nb);
                        $em->flush();
                    }
                    
                    $nb++;
                }
            }
        }
        $em->flush();
        oci_free_statement($csr);
        $output->writeln("Fin de l'import");
        
        oci_close ($this->ORA) ;
    }
}
        
        
        
        