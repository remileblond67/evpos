<?php

namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;

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
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur');
        $repAppli = $em->getRepository('EVPOSaffectationBundle:Application');
        $repAcces = $em->getRepository('EVPOSaffectationBundle:AccesUtilAppli');
        
        // Connexion à la base de données GAP
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pgap";
        $this->ORA = oci_connect ($user , $password , $sid) ;
        if (! $this->ORA) {
		  print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ; 
		  exit () ; 
		}
        
        // Suppression des anciens accés
        $output->write("Suppression des anciens accés... ");
        $listeAcces = $repAcces->getListeAccesAppli();
        foreach($listeAcces as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        $output->writeln("OK");
        
        // Récupération de la liste des utilisateurs connus
        $listeUtil = $repUtil->getUtilisateurs();
        
        $nbUtil = 0;
        
        $output->writeln("Import des accés applicatifs à partir de GAP");
        
        $requeteBaza = "select distinct code_application from gap_user_application where matricule=:matricule";
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;

        foreach ($listeUtil as $utilisateur) {
            $matUtilisateur = $utilisateur->getMatUtil();
            
            // Récupération de la liste des accés de l'utilisateur dans GAP
            oci_bind_by_name($csr, ':matricule', $matUtilisateur);
            oci_execute ($csr) ;
            
            while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
                $codeApplication = $row["CODE_APPLICATION"] ;
                
                if ($repAppli->isApplication($codeApplication)) {
                    $application = $repAppli->getApplication($codeApplication);
                    
                    // Création de l'accés
                    $newAcces = new AccesUtilAppli();
                    
                    $newAcces->setAppliAcces($application);
                    $newAcces->setUtilAcces($utilisateur);
                    $newAcces->setSourceImport("Import GAP du ".date("d/m/Y"));
                    
                    $em->persist($newAcces);
                }
            }
            $nbUtil++;
            if ($nbUtil%100 == 0) 
                $output->write($nbUtil." ");
        }
        oci_free_statement($csr);
        $output->writeln("OK");
        $output->write("Validation en base...");
        $em->flush();
        $output->writeln("Fin de l'import");
        
        oci_close ($this->ORA) ;
        
        // Mise à jour des accés applicatifs de l'ensemble des services
        $output->writeln("Report des accés sur les services");
        
        $output->write("Suppression des accés existants de tous les services...");
        $listeAcces = $em->getRepository('EVPOSaffectationBundle:AccesServiceAppli')->getListeAccesServiceAppli();
        foreach($listeAcces as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        $output->writeln("OK");        
        
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
        }
        $output->writeln("Fin d'import");
        $em->flush();
        $output->writeln("Import validé");
        
        $output->writeln("Fin du traitement");
    }
}
        
        
        
        
