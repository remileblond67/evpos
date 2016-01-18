<?php

namespace EVPOS\affectationBundle\UpdateSuapp;

use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\UO;
use EVPOS\affectationBundle\Entity\Secteur;

class EVPOSUpdateSuapp {

    private $doctrine, $ORA;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;

        $user = $this->getContainer()->getParameter('oracle_user');
    		$password = $this->getContainer()->getParameter('oracle_pwd');
    		$sid = "psuapp";

        $this->ORA = oci_connect ($user , $password , $sid) ;
    }

    public function __destruct() {
        oci_close ($this->ORA) ;
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
