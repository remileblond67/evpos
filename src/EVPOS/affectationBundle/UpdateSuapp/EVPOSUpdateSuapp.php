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
    }
    
    public function __destruct() {
        oci_close ($this->ORA) ;
    }
    
    /**
     * Mise à jour de la liste des applications à partir de SUAPP
     */
    public function importAppliSuapp() {
        $em = $this->doctrine->getManager();

        // Marquage des applications existantes
        $listeAppli = $em->getRepository('EVPOSaffectationBundle:Application')->findAll();
        foreach ($listeAppli as $appli) {
            $appli->setExisteSuapp(FALSE);
        }
        unset($listeAppli);
        
        // Marquage des UO existantes
        $listeUo = $em->getRepository('EVPOSaffectationBundle:UO')->getListeUo();
        foreach ($listeUo as $uo) {
            $em->setMigMoca = false;
        }
        unset($listeUo);
    
		// Récupération de la liste des applications dans SUAPP
		$requeteSUAPP = "SELECT distinct  a.code_appli code,
         a.nom_appli nom,
         a.desc_appli description,
         a.nat_appli nature,
         a.dispo_moca disponible,
         a.code_service
          FROM   app_application a, app_module m
         WHERE   (date_cloture IS NULL OR date_cloture < '1/6/2015')
                 AND nat_appli IN ('AS', 'AI')
                 and a.code_appli = m.code_appli and (m.mig_moca is null or m.mig_moca = 'o')";
        
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        
        oci_execute ($csr) ;

        $nbAppli = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeAppli = strtoupper($row["CODE"]) ;
            $nomAppli = utf8_encode($row["NOM"]);
            $descAppli = utf8_encode($row["DESCRIPTION"]);
            $natAppli = $row['NATURE'];
            $dispoMoca = $row['DISPONIBLE'];
            $codeService = strtoupper($row["CODE_SERVICE"]);
            
            // Recherche du service
            $serviceAppli = $em->getRepository('EVPOSaffectationBundle:Service')
                ->getService($codeService)
            ;
            
            // Recherche de l'application 
            $appliExistante = $em->getRepository('EVPOSaffectationBundle:Application')
                ->isApplication($codeAppli)
            ;

            // L'application existe-elle dans la base ?
            if($appliExistante)
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
            else
                $appli = new Application();
  
            $appli->setCodeAppli($codeAppli);
            $appli->setNomAppli($nomAppli);
            $appli->setDescAppli($descAppli);
            $appli->setNatAppli($natAppli);
            $appli->setDispoMoca($dispoMoca);
            $appli->setServiceAppli($serviceAppli);
            $appli->setExisteSuapp(TRUE);
            
            $em->persist($appli);
            $nbAppli++;
        }
        $em->flush();
        oci_free_statement($csr);
        
        // Suppressions des applications non répertoriées dans SUAPP
        $listeAppli = $em->getRepository('EVPOSaffectationBundle:Application')->getAppliNonSuapp();
        $nbSuppr = 0;
        foreach ($listeAppli as $appli) {
            $em->remove($appli);
            $nbSuppr++;
        }
        unset($listeAppli);
        $em->flush();
                
        $message = $nbAppli . " applications importées (".$nbSuppr." suppression)";
        return $message;
	}
    
    /**
     * Mise à jour de la liste des UO à partir de SUAPP
     */
    public function importUoSuapp() {
        // Récupération de la liste des UO depuis SUAPP
        $requeteSUAPP = "select m.id_module,code_appli,lib_module,translate(mig_moca, 'on', '10') mig_moca
                         from app_module m";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        
        $em = $this->doctrine->getManager();
        $nbUo = 0;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeUo = strtoupper($row["ID_MODULE"]) ;
            $codeAppli = strtoupper($row["CODE_APPLI"]);
            $nomUo = utf8_encode($row["LIB_MODULE"]);
            $migMoca = $row["MIG_MOCA"];
            
            // L'application existe-elle dans la base ?
            if($em->getRepository('EVPOSaffectationBundle:Application')->isApplication($codeAppli)) {
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
                
                if($em->getRepository('EVPOSaffectationBundle:Uo')->isUo($codeUo))
                    $uo = $em->getRepository('EVPOSaffectationBundle:Uo')->getUo($codeUo);
                else
                    $uo = new UO();
                
                $uo->setCodeUo($codeUo);
                $uo->setAppli($appli);
                $uo->setNomUo($nomUo);
                $uo->setMigMoca($migMoca);
                $uo->setTypePoste("");
                $uo->setAncienCitrix(FALSE);
                
                $em->persist($uo);
                $nbUo++;
            }
        }
        $em->flush();
        oci_free_statement($csr);
        
        // Mise à jour du type de poste client
        $requeteSUAPP = "SELECT m.id_module, c.type_poste_client FROM app_module m, app_contr_poste_client c  WHERE m.id_module = c.id_module AND c.type_poste_client LIKE 'MOCA%'";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeUo = strtoupper($row["ID_MODULE"]) ;
            $typePoste = strtoupper($row["TYPE_POSTE_CLIENT"]);
            
            if($em->getRepository('EVPOSaffectationBundle:Uo')->isUo($codeUo)) {
                $uo = $em->getRepository('EVPOSaffectationBundle:Uo')->getUo($codeUo);
                $uo->appendTypePoste($typePoste);
                $em->persist($uo);
            }
        }
        $em->flush();
        oci_free_statement($csr);
        
        // Mise à jour de la disponibilité dans l'ancienne ferme Citrix
        $requeteSUAPP = "SELECT m.id_module FROM app_module m, app_contr_poste_client c  WHERE m.id_module = c.id_module AND c.type_poste_client LIKE 'CITRIX_XA5'";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeUo = strtoupper($row["ID_MODULE"]) ;
            
            if($em->getRepository('EVPOSaffectationBundle:Uo')->isUo($codeUo)) {
                $uo = $em->getRepository('EVPOSaffectationBundle:Uo')->getUo($codeUo);
                $uo->setAncienCitrix(TRUE);
                $em->persist($uo);
            }
        }
        $em->flush();
        oci_free_statement($csr);
        
        
        $message = $nbUo . " UO importées";
        return $message;
    }
    
    /**
     * Mise à jour de la liste des CPI d'application à partir de SUAPP
     */
    public function importCpiSuapp() {
        // Récupération des CPI depuis SUAPP
        $requeteSUAPP = "select i.code_appli, i.mat_util
                        from app_intervient i, app_application a
                        where i.code_appli = a.code_appli and a.date_cloture is null and (inter_date_fin is null or inter_date_fin < '1/7/2015') and id_role_int = 'CPI'
                        and suppleant = 0 and  trans_compet = 0 and INTER_DATE_FIN is null";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        
        $em = $this->doctrine->getManager();
        $nb = 0;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeAppli = strtoupper($row["CODE_APPLI"]) ;
            $matUtil = $row["MAT_UTIL"];
            
            // L'application et l'utilisateur existent-ils dans la base ?
            if($em->getRepository('EVPOSaffectationBundle:Application')->isApplication($codeAppli) && $em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtil)) {
                $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
                $cpi = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
               
                $appli->setCpi($cpi);
                
                $em->persist($appli);
                $nb++;
            }
        }
        $em->flush();
        
        $message = "Mise à jour du CPI de " . $nb . " applications.";
        return $message;
    }
}

