<?php
namespace EVPOS\affectationBundle\UpdateGap;

use EVPOS\affectationBundle\Entity\Utilisateur;
use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\Service;
use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesServiceAppli;
use Doctrine\Common\Collections\ArrayCollection;

class EVPOSUpdateGap {
    
    private $doctrine;
    
    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
        
        // Connexion à la base de données GAP
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
        // Fermeture de l'accès à la base GAP
        oci_close ($this->ORA) ;
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
        
        // Liste pour mémoriser les applications déjà  traitées
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
        
        $message = "Mise à jour de ".$nbAcces." accès applicatifs des ".$nbUtil." utilisateurs du service ".$codeService;
        return $message;
    }
    
    /**
     * Mise à jour des RIU à partir de GAP
     */
    public function updateRiu() {
        $nbRiu = 0;
        $em = $this->doctrine->getManager();
        $rUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur');

        
        $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->getServices();
        foreach ($listeService as $service) {
            // Suppression des anciens RIU
            foreach($service->getListeRiu() as $riu) {
                $service->removeListeRiu($riu);
            }
            
            $codeService = $service->getCodeService();
            
            // Recherche, dans GAP, la liste des RIU du service
            $requeteGap = "select matricule from GAP_NT_RIU where code_service = '".$codeService."'";
            $csr = oci_parse ( $this->ORA , $requeteGap) ;
            oci_execute ($csr) ;
            
            while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
                $matricule = $row["MATRICULE"] ;
                
                // Recherche de l'utilisateur correspondant au matricule
                if ($rUtil->isUtilisateur($matricule)) {
                    $utilisateur = $rUtil->getUtilisateur($matricule);
                
                    // Enregistrement des RIU dans le service
                    $service->addListeRiu($utilisateur);
                }
            }
            oci_free_statement($csr);
            $nbRiu++;
        }
        
        $em->flush();
        
        $message = "Mise à jour de ".$nbRiu." RIU";
        return $message;
    }
}
