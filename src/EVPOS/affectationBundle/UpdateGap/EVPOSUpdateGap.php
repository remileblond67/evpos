<?php
namespace EVPOS\affectationBundle\UpdateGap;

use EVPOS\affectationBundle\Entity\Utilisateur;
use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\Service;
use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;
use Doctrine\Common\Collections\ArrayCollection;

class EVPOSUpdateGap {
    
    private $doctrine, $ORA;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine; 
        
        $user = "970595";
		$password = "M2p4CUS";
		$sid = "pgap";
		
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
     * Mise � jour de la liste des acc�s � partir de GAP
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
                
                // Cr�ation de l'acc�s uniquement s'il n'existe pas pr�alablement
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
        $message = "Import de ".$nb." acc�s applicatifs.";
        return $message;
    }
    
    
    /**
     * Mise � jour des acc�s applicatifs de l'ensemble des services
     */
    public function updateAccesServices() {
        $em = $this->doctrine->getManager();
        $listeServices = $em->getRepository('EVPOSaffectationBundle:Service')->getServices();
        
        $nb = 0;
        foreach($listeServices as $service) {
            $this->updateAccesService($service->getCodeService());
            $nb++;
        }
        
        $message = "Mise � jour des acc�s de ".." services";
        return $message;
    }
    
    /**
     * Mise � jour des acc�s applicatifs d'un service
     */
    public function updateAccesService($codeService) {
        $em = $this->doctrine->getManager();
        $nbUtil = 0;
        $nbAcces = 0;
        
        $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
        
        // Suppression des acc�s existants du service
        foreach ($service->getListeAcces() as $acces) {
            $em->remove($acces);
        }
        $em->flush();
        
        $listeUtilisateurs = $service->getListeUtilisateurs();
        
        $asa = $em->getRepository('EVPOSaffectationBundle:AccesServiceAppli');
        
        // Liste pour mémoriser les applications déjà traitées
        $listeAppli = array();

        foreach ($listeUtilisateurs as $util) {
            foreach ($util->getListeAcces() as $acces) {
                $listeAppli[] = $acces->getAppliAcces()->getCodeAppli();
                $nbAcces++;
            }
            $nbUtil++;
        }
        $listeAppli = array_unique($listeAppli);

        foreach ($listeAppli as $codeAppli) {
            $newAcces = new AccesServiceAppli();
            
            $appli = $em->getRepository('EVPOSaffectationBundle:Application')->getApplication($codeAppli);
            
            $newAcces->setServiceAcces($service);
            $newAcces->setAppliAcces($appli);
            $newAcces->setSourceImport("NEW");

            $em->persist($newAcces);
        }
        $em->flush();
        
        $message = "Mise � jour de ".$nbAcces." acc�s applicatifs des ".$nbUtil." utilisateurs du service ".$codeService;
        return $message;
    }
}
