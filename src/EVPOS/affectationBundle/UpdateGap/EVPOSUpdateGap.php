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
        
        // Liste pour mÃ©moriser les applications dÃ©jÃ  traitÃ©es
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
}
