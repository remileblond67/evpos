<?php

namespace EVPOS\affectationBundle\UpdateBaza;

use EVPOS\affectationBundle\Entity\Direction;
use EVPOS\affectationBundle\Entity\Service;

class EVPOSUpdateBaza {
    
    private $doctrine, $ORA;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine; 
        
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pbaza";
		
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
     * Mise à jour de la liste des directions à partir de BAZA
     */
    public function importDirections() {
        $requeteBaza = "select code_direction, nvl(lib_long_direction, ' ') lib_long_direction from baz_direction";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $em = $this->doctrine->getManager();
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $codeDirection = $row["CODE_DIRECTION"] ;
            $libDirection = utf8_encode($row["LIB_LONG_DIRECTION"]);
            
            if ($em->getRepository('EVPOSaffectationBundle:Direction')->isDirection($codeDirection))
                $newDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->getDirection($codeDirection);
            else
                $newDirection = new Direction();
            
            $newDirection->setCodeDirection($codeDirection);
            $newDirection->setLibDirection($libDirection);
            
            $em->persist($newDirection);
        }
        $em->flush();
        
        oci_free_statement($csr);
    }

    /**
     * Mise à jour de la liste des services à partir de BAZA
     */
    public function importServices() {
        $requeteBaza = "select code_service, code_direction, nvl(description_service, ' ') description_service from baz_service where flag is null";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $em = $this->doctrine->getManager();
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $codeService = $row["CODE_SERVICE"] ;
            $codeDirection = $row["CODE_DIRECTION"] ;
            $libService = utf8_encode($row["DESCRIPTION_SERVICE"]);
            
            if ($em->getRepository('EVPOSaffectationBundle:Service')->isService($codeService))
                $newService = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
            else
                $newService = new Service();
                
            
            $newService->setDirection($em->getRepository('EVPOSaffectationBundle:Direction')->getDirection($codeDirection));
            $newService->setCodeService($codeService);
            $newService->setLibService($libService);
            
            $em->persist($newService);
        }
        $em->flush();
        
        oci_free_statement($csr);
    }
}