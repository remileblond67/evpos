<?php

namespace EVPOS\affectationBundle\UpdateSuapp;

use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\UO;

class EVPOSUpdateSuapp {
    
    private $doctrine, $ORA;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine; 
        
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "psuapp";
		
        $this->ORA = oci_connect ($user , $password , $sid) ;
        if (! $this->ORA) {
		  print "Erreur de connexion à la base de données $sid avec l'utilisateur $user." ; 
		  exit () ; 
		}
    }
    
    public function __destruct() {
        oci_close ($this->ORA) ;
    }
    
    /**
     * Mise à jour de la liste des applications à partir de SUAPP
     */
    public function importAppliSuapp() {
		// Récupération de la liste des applications dans SUAPP
		$requeteSUAPP = "select code_appli code, nom_appli nom, desc_appli description, nat_appli nature, dispo_moca disponible from app_application where (date_cloture is null or date_cloture < '1/6/2015') and nat_appli in ('AS', 'AI')";
        
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        
        oci_execute ($csr) ;

        $em = $this->doctrine->getManager();
        $nbAppli = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $codeAppli = $row["CODE"] ;
            $nomAppli = utf8_encode($row["NOM"]);
            $descAppli = utf8_encode($row["DESCRIPTION"]);
            $natAppli = $row['NATURE'];
            $dispoMoca = $row['DISPONIBLE'];
            
            // Recherche de l'application dans la base
            $appliExistante = $em->getRepository('EVPOSaffectationBundle:Application')
                ->isApplication($codeAppli)
            ;
            // L'application existe-elle dans la base ?
            if($appliExistante)
                $newAppli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
            else
                $newAppli = new Application();
  
            $newAppli->setCodeAppli($codeAppli);
            $newAppli->setNomAppli($nomAppli);
            $newAppli->setDescAppli($descAppli);
            $newAppli->setNatAppli($natAppli);
            $newAppli->setDispoMoca($dispoMoca);
            
            $em->persist($newAppli);
            $nbAppli++;
        }
        $em->flush();
        
        oci_free_statement($csr);
        
        $message = $nbAppli . " applications importées";
        return $message;
	}
    
    /**
     * Mise à jour de la liste des UO à partir de SUAPP
     */
    public function importUoSuapp() {
        // Récupération de la liste des UO depuis SUAPP
        $requeteSUAPP = "select id_module,code_appli,lib_module,translate(mig_moca, 'on', '10') mig_moca
                         from app_module where (mig_moca is null or mig_moca = 'o')";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        
        $em = $this->doctrine->getManager();
        $nbUo = 0;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $codeUo = $row["ID_MODULE"] ;
            $codeAppli = $row["CODE_APPLI"];
            $nomUo = utf8_encode($row["LIB_MODULE"]);
            $migMoca = $row["MIG_MOCA"];
            
            // L'application existe-elle dans la base ?
            if($em->getRepository('EVPOSaffectationBundle:Application')->isApplication($codeAppli)) {
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
                
                if($em->getRepository('EVPOSaffectationBundle:Uo')->isUo($codeUo))
                    $newUo = $em->getRepository('EVPOSaffectationBundle:Uo')->getUo($codeUo);
                else
                    $newUo = new UO();
                
                $newUo->setCodeUo($codeUo);
                $newUo->setAppli($appli);
                $newUo->setNomUo($nomUo);
                $newUo->setMigMoca($migMoca);
                
                $em->persist($newUo);
                $nbUo++;
            }
        }
        $em->flush();
        
        $message = $nbUo . " UO importées";
        return $message;
    }
}