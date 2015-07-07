<?php
namespace EVPOS\affectationBundle\UpdateGap;

use EVPOS\affectationBundle\Entity\Utilisateur;
use EVPOS\affectationBundle\Entity\Application;

class EVPOSUpdateGap {
    
    private $doctrine, $ORA;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine; 
        
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pgap";
		
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
     * Mise à jour de la liste des accès à partir de GAP
     */
    public function importAcces() {
        $requeteBaza = "select distinct matricule, code_application from gap_user_application";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $em = $this->doctrine->getManager();
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $matUtilisateur = $row["MATRICULE"] ;
            $codeApplication = $row["CODE_APPLICATION"] ;
            
            if ($em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtilisateur) &&
                    $em->getRepository('EVPOSaffectationBundle:Application')->isApplication($codeApplication)) {
                $newUtilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtilisateur);
                $newApplication = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeApplication);
            }
        }
        oci_free_statement($csr);
    }
}