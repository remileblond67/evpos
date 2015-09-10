<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;
use EVPOS\affectationBundle\Entity\AccesUtilUo;
use EVPOS\affectationBundle\Entity\AccesServiceUo;

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
        $repUo = $em->getRepository('EVPOSaffectationBundle:UO');
        $repAcces = $em->getRepository('EVPOSaffectationBundle:AccesUtilAppli');
        $repAccesUo = $em->getRepository('EVPOSaffectationBundle:AccesUtilUo');
        
        
        // Connexion à la base de données GAP
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pgap";
        $this->ORA = oci_connect ($user , $password , $sid) ;
        if (! $this->ORA) {
		  print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ; 
		  exit () ; 
		}
        
        // Suppression des anciens accès aux applications
        $output->write("Suppression des anciens accès aux applications... ");
        $listeAcces = $repAcces->getListeAccesAppli();
        foreach($listeAcces as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        $output->writeln("OK");
        
        // Suppression des anciens accès aux UO
        $output->write("Suppression des anciens accès aux UO... ");
        $listeAccesUo = $repAccesUo->findAll();
        foreach($listeAccesUo as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        $output->writeln("OK");
        
        
        
        // Récupération de la liste des utilisateurs connus
        $listeUtil = $repUtil->getUtilisateurs();
        
        $nbUtil = 0;
        
        $output->writeln("Import des accès aux applications à partir de GAP");
        
        $requeteBaza = "select distinct code_application from gap_user_application where matricule=:matricule";
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;

        foreach ($listeUtil as $utilisateur) {
            $matUtilisateur = $utilisateur->getMatUtil();
            
            // Récupération de la liste des accés de l'utilisateur dans GAP
            oci_bind_by_name($csr, ':matricule', $matUtilisateur);
            oci_execute ($csr) ;
            
            while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
                $codeApplication = strtoupper($row["CODE_APPLICATION"]) ;
                
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
        $output->writeln("OK");
        $output->writeln("Fin de l'import");
        
        
        $output->writeln("Import des accès aux UO à partir de GAP");
        $nbUtil = 0;
        
        $requeteBaza = "select REGEXP_REPLACE(REGEXP_REPLACE(upper(ntmgname), '^GA_', ''),'_P$','') CODE_UO from baz_member where upper(ntmgname) like 'GA\_%\_P' escape '\' and ntmuid=:matricule";
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;

        foreach ($listeUtil as $utilisateur) {
            $matUtilisateur = $utilisateur->getMatUtil();
            
            // Récupération de la liste des accés de l'utilisateur dans GAP
            oci_bind_by_name($csr, ':matricule', $matUtilisateur);
            oci_execute ($csr) ;
            
            while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
                $codeUo = $row["CODE_UO"] ;
                
                if ($repUo->isUo($codeUo)) {
                    $uo = $repUo->getUo($codeUo);
                    
                    // Création de l'accés
                    $newAcces = new AccesUtilUo();
                    
                    $newAcces->setUoAcces($uo);
                    $newAcces->setUtilAcces($utilisateur);
                    $newAcces->setSourceImport("Import GAP du ".date("d/m/Y"));
                    
                    $em->persist($newAcces);
                }
            }
            $nbUtil++;
            if ($nbUtil%100 == 0) 
                $output->write($nbUtil." ");
            if ($nbUtil%1000 == 0) 
                $em->flush();
        }
        oci_free_statement($csr);
        $output->writeln("OK");
        $output->write("Validation en base...");
        $em->flush();
        $output->writeln("Fin de l'import");
        
        oci_close ($this->ORA) ;
                
        $output->writeln("Fin du traitement");
    }
}
