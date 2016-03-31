<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Secteur;
use EVPOS\affectationBundle\Entity\Application;
use EVPOS\affectationBundle\Entity\UO;

/**
 * Import des informations sur les applications/UO à partir de la base SUAPP
 */
class ImportSuappCommand extends ContainerAwareCommand
{
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_suapp')
            ->setDescription('Import des données SUAPP')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $user = $this->getContainer()->getParameter('oracle_user');
    		$password = $this->getContainer()->getParameter('oracle_pwd');
    		$sid = "pbaza";

        $this->ORA = oci_connect ($user , $password , $sid) ;

        // Récupération de la liste des secteurs depuis SUAPP
        $output->write("Import des secteurs... ");
        $requeteSUAPP = "SELECT ID_SECTEUR, LIB_SECTEUR FROM SUAPP.APP_SECTEUR_INFORMATIQUE";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeSecteur = $row['ID_SECTEUR'];
            $libSecteur = $row['LIB_SECTEUR'];

            if ($secteur = $em->getRepository('EVPOS\affectationBundle\Entity\Secteur')->find($codeSecteur)) {
                $secteur->setLibSecteur($libSecteur);
            } else {
                $secteur = new Secteur;
                $secteur->setCodeSecteur($codeSecteur);
                $secteur->setLibSecteur($libSecteur);
            }
            $em->persist($secteur);
        }
        $em->flush();
        oci_free_statement($csr);
        $output->writeln("OK");

        $output->write("Import des applications... ");
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

        $output->writeln($nbAppli . " applications importées (".$nbSuppr." suppression)");

        // Affectation des applications aux secteurs
        $output->write("Affectation des applications aux secteurs... ");
        $requeteSUAPP = "select id_secteur, code_appli from app_est_geree where fin_gestion_secteur is null or fin_gestion_secteur > sysdate";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeSecteur = $row['ID_SECTEUR'];
            $codeAppli = $row['CODE_APPLI'];

            if (($secteur = $em->getRepository('EVPOS\affectationBundle\Entity\Secteur')->find($codeSecteur)) &&
                ($appli = $em->getRepository('EVPOS\affectationBundle\Entity\Application')->find($codeAppli)))
            {
                $appli->setSecteur($secteur);
                $em->persist($appli);
            }
        }
        $em->flush();
        $output->writeln("OK");

        /**
         * Mise à jour de la liste des UO à partir de SUAPP
         */
        $output->write("Mise à jour de la liste des UO à partir de SUAPP... ");

        // Positionnement de l'indicateur d'existance des UO
        $listeUo = $em->getRepository('EVPOSaffectationBundle:UO')->findAll();
        foreach ($listeUo as $uo) {
          $uo->setExisteSuapp(FALSE);
          $em->persist($uo);
        }
        $em->flush();

        // Récupération de la liste des UO depuis SUAPP
        $requeteSUAPP = "select m.id_module,code_appli,lib_module,translate(mig_moca, 'on', '10') mig_moca
                         from app_module m";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;

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
                $uo->setAncienCitrix(FALSE);
                $uo->setExisteSuapp(TRUE);

                $em->persist($uo);
                $nbUo++;
            }
        }
        $em->flush();
        oci_free_statement($csr);


        // Mise à jour du type de poste client
        $output->write("Suppression des types de poste des UO : ");
        $listeUo = $em->getRepository('EVPOSaffectationBundle:Uo')->getListeUo();
        foreach ($listeUo as $uo) {
          $uo->delTypePoste();
          $em->persist($uo);
          $output->write($uo->getCodeUo());
        }
        $em->flush();

        $requeteSUAPP = "SELECT distinct m.id_module, c.type_poste_client FROM app_module m, app_contr_poste_client c  WHERE m.id_module = c.id_module AND c.type_poste_client LIKE 'MOCA%'";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;
        while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
            $codeUo = strtoupper($row["ID_MODULE"]) ;
            $typePoste = strtoupper($row["TYPE_POSTE_CLIENT"]);
            $output->write($codeUo." -> ".$typePoste . "...");

            $uo = $em->getRepository('EVPOSaffectationBundle:Uo')->getUo($codeUo);
            if($uo !== NULL) {
                //$uo->appendTypePoste($typePoste);
                //$em->persist($uo);
                $output->writeln("OK");
            } else {
                $output->writeln("Non trouvé");
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
        $output->writeln($nbUo . " UO importées");

        /**
         * Mise à jour de la liste des CPI d'application à partir de SUAPP
         */
        // Récupération des CPI depuis SUAPP
        $requeteSUAPP = "select i.code_appli, i.mat_util
                        from app_intervient i, app_application a
                        where i.code_appli = a.code_appli and a.date_cloture is null and (inter_date_fin is null or inter_date_fin < '1/7/2015') and id_role_int = 'CPI'
                        and suppleant = 0 and  trans_compet = 0 and INTER_DATE_FIN is null";
        $csr = oci_parse ( $this->ORA , $requeteSUAPP) ;
        oci_execute ($csr) ;

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

        $output->writeln("Mise à jour du CPI de " . $nb . " applications.");

        // Suppression des UO qui n'existent pas dans SUAPP
        $output->write("Purge des UO qui n'existent plus dans SUAPP...");
        $listeUoSupprimees = $em->getRepository('EVPOSaffectationBundle:UO')->getUoSupprimees();

        foreach ($listeUoSupprimees as $uo) {
          $output->writeln("Suppression ".$uo->getCodeUo());
          $em->remove($uo);
        }
        $em->flush();
        $output->writeln("OK");
    }
}
