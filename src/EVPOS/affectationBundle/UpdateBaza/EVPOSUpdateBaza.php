<?php
namespace EVPOS\affectationBundle\UpdateBaza;

use EVPOS\affectationBundle\Entity\Direction;
use EVPOS\affectationBundle\Entity\Service;
use EVPOS\affectationBundle\Entity\Utilisateur;

use Symfony\Component\Validator\Constraints\DateTime;


class EVPOSUpdateBaza {
    
    private $doctrine, $ORA;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine; 
        
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pbaza";
		
        $this->ORA = oci_connect ($user , $password , $sid) ;
        if (! $this->ORA) {
            $this->get('request')->getSession()->getFlashBag()->add('erreur', utf8_encode("Erreur de connexion à la base de données $sid avec l'utilisateur $user.")) ; 
		}
    }
    
    public function __destruct() {
        oci_close ($this->ORA) ;
    }
        
    /**
     * Mise à jour de la liste des directions à partir de BAZA
     */
    public function importDirections() {
        $em = $this->doctrine->getManager();
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
        
        $message = "Import de ".$nb." directions";
        
        return $message;
    }

    /**
     * Mise à jour de la liste des services à partir de BAZA
     */
    public function importServices() {
        $em = $this->doctrine->getManager();
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
        }
        unset($listeService);
        $em->flush();
        
        // Création des utilisateurs générique de services

        
        oci_free_statement($csr);
        unset($csr);
        
        $message = "Import de ".$nb." services";
        
        return $message;
    }
    
    /**
     * Mise à jour de la liste des utilisateurs à partir de BAZA
     */
    public function importUtilisateurs() {
        $em = $this->doctrine->getManager();
        
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
        
        $message = "Import de ".$nb." utilisateurs";
        
        // Suppression des utilisateurs qui n'existaient pas dans BAZA
        $listeUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateursSuppr();
        foreach($listeUtil as $util) {
            $em->remove($util);
        }
        $em->flush();
        unset($listeUtil);
        
        // Création des utilisateurs "libre service"
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
        
        return $message;
    }
}
