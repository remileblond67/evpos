<?php
namespace EVPOS\affectationBundle\UpdateBaza;

use EVPOS\affectationBundle\Entity\Direction;
use EVPOS\affectationBundle\Entity\Service;
use EVPOS\affectationBundle\Entity\Utilisateur;

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
        $requeteBaza = "select code_direction, nvl(lib_long_direction, ' ') lib_long_direction from baz_direction where flag is null";
        
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
            
            $nb++;
        }
        $em->flush();
        
        oci_free_statement($csr);
        
        $message = "Import de ".$nb." directions";
        
        return $message;
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
            $nb++;
        }
        $em->flush();
        
        oci_free_statement($csr);
        
        $message = "Import de ".$nb." services";
        
        return $message;
    }
    
    /**
     * Mise à jour de la liste des utilisateurs à partir de BAZA
     */
    public function importUtilisateurs() {
        $requeteBaza = "select ntuid matricule, ntufullnam nom, code_service from baz_user_nt where ntuscript is not null and ntulastlgn is not null and ntufullnam is not null and upper(ntuid) not like '%\__' escape '\'";
        
        $csr = oci_parse ( $this->ORA , $requeteBaza) ;
        oci_execute ($csr) ;
        $em = $this->doctrine->getManager();
        $nb = 0;
         
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
            $matUtil = $row["MATRICULE"];
            $nomUtil = utf8_encode($row["NOM"]);
            // $prenomUtil = utf8_encode($row["PRENOM"]);
            $codeService = utf8_encode($row["CODE_SERVICE"]);
            
            if ($em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtil))
                $newUtilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
            else
                $newUtilisateur = new Utilisateur();
                
            if ($em->getRepository('EVPOSaffectationBundle:Service')->isService($codeService))
                $newUtilisateur->setServiceUtil($em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService));
                
            $newUtilisateur->setMatUtil($matUtil);
            $newUtilisateur->setNomUtil($nomUtil);
            
            $em->persist($newUtilisateur);
            
            $nb++;
        }
        $em->flush();
        
        oci_free_statement($csr);
        
        $message = "Import de ".$nb." utilisateurs";
        
        return $message;
    }
}