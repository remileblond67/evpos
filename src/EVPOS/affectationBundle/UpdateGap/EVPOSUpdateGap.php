<?php
namespace EVPOS\affectationBundle\UpdateGap;

use EVPOS\affectationBundle\Entity\Utilisateur;
use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\Service;
use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;

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
                $application = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeApplication);
                $utilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtilisateur);
                
                // Création de l'accès uniquement s'il n'existe pas préalablement
                if ($em->getRepository('EVPOSaffectationBundle:AccesUtilAppli')->isAccesUtilAppli($application, $utilisateur) == false) {
                    $newAcces = new AccesUtilAppli();
                
                    $newAcces->setAppliAcces($application);
                    $newAcces->setUtilAcces($utilisateur);
                    $newAcces->setSourceImport("GAP");
                    
                    $em->persist($newAcces);
                    
                    // Commit tous les 100 enregistrements
                    if ($nb%100) $em->flush();
                    
                    $nb++;
                }
            }
            $em->flush();
        }
        oci_free_statement($csr);
        $message = "Import de ".$nb." accès applicatifs.";
        return $message;
    }
    
    
    /**
     * Mise à jour des accès applicatifs d'un service
     */
    public function updateAccesService($codeService) {
        $em = $this->doctrine->getManager();
        $nbUtil = 0;
        $nbAcces = 0;
        
        $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
        
        // Suppression des accès existants du service
        foreach ($service->getListeAcces() as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        
        $listeUtilisateurs = $service->getListeUtilisateurs();
        
        $asa = $em->getRepository('EVPOSaffectationBundle:AccesServiceAppli');
        
        foreach ($listeUtilisateurs as $util) {
            foreach ($util->getListeAcces() as $acces) {
                $appliAcces = $acces->getAppliAcces();
                if ($asa->isAccesServiceAppli($appliAcces, $service)) {
                    // $newAcces = $asa->getAccesServiceAppli($appliAcces, $service);
                    // $newAcces->setSourceImport("MAJ");
                } else {
                    $newAcces = new AccesServiceAppli();
                    $newAcces->setServiceAcces($service);
                    $newAcces->setAppliAcces($appliAcces);
                    $newAcces->setSourceImport("NEW");
                }
                $em->persist($newAcces);
                $nbAcces++;
            }
            $nbUtil++;
        }
        $em->flush();
        
        $message = "Mise à jour de ".$nbAcces." accès applicatifs des ".$nbUtil." utilisateurs du service ".$codeService;
        return $message;
    }
}
