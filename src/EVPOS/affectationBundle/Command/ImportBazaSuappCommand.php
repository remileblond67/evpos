<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\Utilisateur;

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
        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pbaza";
		
        $this->ORA = oci_connect ($user , $password , $sid) ;

        $output->writeln("*** Import des directions ***");
        // Marquage des directions existantes
        $listeDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->findAll();
        foreach($listeDirection as $direction) {
            $direction->setExisteBaza(FALSE);
        }
        unset($listeDirection);
        
        $requeteBaza = "select code_direction, nvl(lib_long_direction, ' ') lib_long_direction from baz_direction where flag is null";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeDirection = strtoupper($row["CODE_DIRECTION"]) ;
            $libDirection = utf8_encode($row["LIB_LONG_DIRECTION"]);
            
            if ($em->getRepository('EVPOSaffectationBundle:Direction')->isDirection($codeDirection))
                $direction = $em->getRepository('EVPOSaffectationBundle:Direction')->getDirection($codeDirection);
            else
                $direction = new Direction();
            
            $direction->setCodeDirection($codeDirection);
            $direction->setLibDirection($libDirection);
            $direction->setExisteBaza(TRUE);
            
            $em->persist($direction);
            
            $nb++;
        }
        $em->flush();
        
        oci_free_statement($csr);
        unset($csr);
        
        $output->writeln("Import de ".$nb." directions");
        
        
        $output->writeln("*** Import des services ***");
        // Marquage des services existants
        $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->findAll();
        foreach($listeService as $service) {
            $service->setExisteBaza(FALSE);
        }
        unset($listeService);
        
        $requeteBaza = "select code_service, code_direction, nvl(description_service, ' ') description_service from baz_service where flag is null";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeService = strtoupper($row["CODE_SERVICE"]) ;
            $codeDirection = strtoupper($row["CODE_DIRECTION"]) ;
            $libService = utf8_encode($row["DESCRIPTION_SERVICE"]);
            
            if ($em->getRepository('EVPOSaffectationBundle:Service')->isService($codeService))
                $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
            else
                $service = new Service();
                
            
            $service->setDirection($em->getRepository('EVPOSaffectationBundle:Direction')->getDirection($codeDirection));
            $service->setCodeService($codeService);
            $service->setLibService($libService);
            $service->setExisteBaza(TRUE);
            
            $em->persist($service);
            $nb++;
        }
        $em->flush();
        
        // Suppression des services non retrouvés dans BAZA
        $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->getServicesNonBaza();
        foreach($listeService as $service) {
            $em->remove($service);
            $output->writeln("- suppression de ".$service->getCodeService());
        }
        unset($listeService);
        $em->flush();
        
        
        oci_free_statement($csr);
        unset($csr);
        
        $output->writeln("Import de ".$nb." services");
        
        
        $output->writeln("*** Import des utilisateurs ***");
        // Marquage des utilisateurs existants
        $listeUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->findAll();
        foreach($listeUtil as $util) {
            $util->setExisteBaza(FALSE);
            $em->persist($util);
        }
        $em->flush();
        unset($listeUtil);
        
        $requeteBaza = "select ntuid matricule, ntufullnam nom, code_service, to_char(NTULASTLGN, 'YYYY-MM-DD') NTULASTLGN from baz_user_nt where ntuscript is not null and ntufullnam is not null and upper(ntuid) not like '%\__' escape '\'";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $matUtil = $row["MATRICULE"];
            $nomUtil = utf8_encode($row["NOM"]);
            $codeService = strtoupper(utf8_encode($row["CODE_SERVICE"]));
            $lastLogin = $row["NTULASTLGN"];
            
            if ($em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtil))
                $utilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
            else
                $utilisateur = new Utilisateur();
                
            if ($em->getRepository('EVPOSaffectationBundle:Service')->isService($codeService))
                $utilisateur->setServiceUtil($em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService));
                
            $utilisateur->setMatUtil($matUtil);
            $utilisateur->setNomUtil($nomUtil);
            if ($lastLogin !== null) {
                $utilisateur->setLastLogin(new \DateTime($lastLogin));
            } else {
                $utilisateur->setLastLogin(new \DateTime("1/1/1900"));
            }
            $utilisateur->setExisteBaza(TRUE);
            
            $em->persist($utilisateur);
            
            $nb++;
        }
        $em->flush();
        
        oci_free_statement($csr);
        unset($csr);
        
        $output->writeln("Import de ".$nb." utilisateurs");
        
        // Suppression des utilisateurs qui n'existaient pas dans BAZA
        $listeUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateursSuppr();
        foreach($listeUtil as $util) {
            $em->remove($util);
            $output->writeln("- Suppression de ".$util->getNomUtil());
        }
        $em->flush();
        unset($listeUtil);
        
        // Création des utilisateurs "libre service"
        $output->write("Création des utilisateurs libre service... ");
        $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->findAll();
        foreach($listeService as $service) {
            $util = new Utilisateur();
            $util->setMatUtil("LS_".$service->getCodeService());
            $util->setNomUtil("Libre service ".$service->getCodeService());
            $util->setServiceUtil($service);
            $em->persist($util);
        }
        $em->flush();
        unset($listeService);
        $output->writeln("OK");
        
        $output->writeln("Fin du traitement");
        
        oci_close ($this->ORA) ;
	}
}