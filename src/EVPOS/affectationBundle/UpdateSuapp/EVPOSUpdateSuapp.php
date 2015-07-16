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
		  print "Erreur de connexion � la base de donn�es $sid avec l'utilisateur $user." ; 
		  exit () ; 
		}
    }
    
    public function __destruct() {
        oci_close ($this->ORA) ;
    }
    
    /**
     * Mise � jour de la liste des applications � partir de SUAPP
     */
    public function importAppliSuapp() {
        $em = $this->doctrine->getManager();

        // Suppression des UO existantes
        $listeUo = $em->getRepository('EVPOSaffectationBundle:UO')->getListeUo();
        foreach ($listeUo as $uo) {
            $em->remove($uo);
        }
    
		// R�cup�ration de la liste des applications dans SUAPP
		$requeteSUAPP = "SELECT distinct  a.code_appli code,
         a.nom_appli nom,
         a.desc_appli description,
         a.nat_appli nature,
         a.dispo_moca disponible
          FROM   app_application a, app_module m
         WHERE   (date_cloture IS NULL OR date_cloture < '1/6/2015')
                 AND nat_appli IN ('AS', 'AI')
                 and a.code_appli = m.code_appli and (m.mig_moca is null or m.mig_moca = 'o')";
        
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        
        oci_execute ($csr) ;

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
        
        $message = $nbAppli . " applications import�es";
        return $message;
	}
    
    /**
     * Mise � jour de la liste des UO � partir de SUAPP
     */
    public function importUoSuapp() {
        // R�cup�ration de la liste des UO depuis SUAPP
        $requeteSUAPP = "select id_module,code_appli,lib_module,translate(mig_moca, 'on', '10') mig_moca
                         from app_module";
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
        
        $message = $nbUo . " UO import�es";
        return $message;
    }
    
    /**
     * Mise � jour de la liste des CPI d'application � partir de SUAPP
     */
    public function importCpiSuapp() {
        // R�cup�ration des CPI depuis SUAPP
        $requeteSUAPP = "select i.code_appli, i.mat_util
                        from app_intervient i, app_application a
                        where i.code_appli = a.code_appli and a.date_cloture is null and (inter_date_fin is null or inter_date_fin < '1/7/2015') and id_role_int = 'CPI'
                        and suppleant = 0 and  trans_compet = 0 and INTER_DATE_FIN is null";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        
        $em = $this->doctrine->getManager();
        $nb = 0;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $codeAppli = $row["CODE_APPLI"] ;
            $matUtil = $row["MAT_UTIL"];
            
            // L'application et l'utilisateur existent-ils dans la base ?
            if($em->getRepository('EVPOSaffectationBundle:Application')->isApplication($codeAppli) and $em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtil)) {
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
                $cpi = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
               
                $appli->setCpi($cpi);
                
                $em->persist($appli);
                $nb++;
            }
        }
        $em->flush();
        
        $message = "Mise � jour du CPI de " . $nb . " applications.";
        return $message;
    }
}