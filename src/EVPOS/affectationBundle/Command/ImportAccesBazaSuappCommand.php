<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilUo;

/**
 * Import des accès applicatifs à partir des bases GAP et BAZA 
 * - GAP : accès aux applications
 * - BAZA : accès aux UO
 */
class ImportAccesBazaSuappCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_acces_baza_suapp')
            ->setDescription('Import des accès applicatifs décrits dans SUAPP à partir de BAZA')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        ini_set('memory_limit', -1);
        gc_enable();
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        
        // Connexion à la base de données GAP
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pgap";
        $this->ORA = oci_connect ($user , $password , $sid) ;
        if (! $this->ORA) {
		  print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ; 
		  $output->writeln("Impossible de se connecter à la base de données");
		} else {
            $output->writeln("Import des accès aux UO à partir de BAZA et des groupes déclarés dans SUAPP");
            $nbUtil = 0;
            
            $requeteBaza = "select distinct a.id_module code_uo, m.ntmuid mat_util
                            from app_role_appli a, baz_member m
                            where a.code_env = 'PROD' and upper(a.code_role_appli) = upper(m.ntmgname)";
            
            $csr = oci_parse ( $this->ORA , $requeteBaza) ;
            oci_execute ($csr) ;
            
            $nb = 0;
            
            while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
                $codeUo = strtoupper($row["CODE_UO"]) ;
                $matUtil = strtoupper($row["MAT_UTIL"]) ;
                
                // Recherche de l'UO et de l'utilisateur concernés
                $uo = $em->getRepository('EVPOSaffectationBundle:UO')->getUo($codeUo);
                $util = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
            
                if ($uo !== null and $util !== null) {
                    $acces = $em->getRepository('EVPOSaffectationBundle:AccesUtilUo')->getAccesUtilUo($util, $uo);
                    if ($acces == null) {
                        $acces = new AccesUtilUo();
                        $acces->setUtilAcces($util);
						$acces->setUoAcces($uo);
						$acces->setSourceImport("Report depuis groupe SUAPP");
						$em->persist($acces);
                        $nb++;
                        if ($nb%1000 == 0) {
                            $em->flush();
                        }
                        $output->write('.');
                    }
                    $output->write('o');
                } else {
                    $output->write('o');
                }
            }
            oci_free_statement($csr);
            $output->writeln("OK");
            $output->write("Validation en base...");
            $em->flush();
            $output->writeln("Fin de l'import");
            
            
            oci_close ($this->ORA) ;
        }
        $output->writeln("Fin du traitement");
    }
}
