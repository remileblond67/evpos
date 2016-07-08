<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\Direction;
use EVPOS\affectationBundle\Entity\Service;
use EVPOS\affectationBundle\Entity\Utilisateur;
use EVPOS\affectationBundle\Entity\CtrlServiceInconnu;

/**
* Import des accés applicatifs à partir de la base GAP
* - GAP : accés aux applications
*/
class ImportBazaCommand extends ContainerAwareCommand
{
  protected function configure() {
    parent::configure();
    $this
    ->setName('evpos:import_baza')
    ->setDescription('Import des données BAZA')
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $em = $this->getContainer()->get('doctrine')->getManager();
    if (strpos(getcwd(), "prod") !== false) {
      $env = "prod";
    } else {
      $env = "dev";
    }
    $output->writeln("Environnement : ".$env);

    $user = $this->getContainer()->getParameter('oracle_user');;
    $password = $this->getContainer()->getParameter('oracle_pwd');;
    $sid = "pbaza";

    // Passer cette variable à TRUE pour déclencher une mise à jour des directions depuis BAZA
    $updateDirection = FALSE;

    $this->ORA = oci_connect ($user , $password , $sid) ;

    if ($updateDirection) {
      $output->writeln("*** Import des directions ***");
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

      $output->writeln("Import de ".$nb." directions");
    }

    $output->writeln("*** Import des services depuis BAZA ***");
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


      if ($updateDirection) {
        $service->setDirection($em->getRepository('EVPOSaffectationBundle:Direction')->getDirection($codeDirection));
      }
      $service->setCodeService($codeService);
      $service->setCodeDirection($codeDirection);
      $service->setLibService($libService);
      $service->setExisteBaza(TRUE);

      $em->persist($service);
      $nb++;
    }
    $em->flush();

    $output->writeln("*** Fusion des données d'entités GPARC ***");
    $fileName = "/home/data/evpos/".$env."/gparc/entites.csv";
    $codeInconnu = [];
    if (file_exists($fileName)) {
      $csvFile = fopen($fileName, 'r');
      $nbLine = 0;
      while (($data = fgetcsv($csvFile, 0, ';')) !== FALSE) {
        if ($nbLine>0) {
          $lib = trim($data[0]);
          $actif = trim($data[2]);
          $codeService = trim($data[6]);
          $codeSirh = trim($data[7]);

          if ($actif == "1" && $codeService != "" && $codeService != "-") {
            // Mise à jour du service concerné
            if ($em->getRepository('EVPOSaffectationBundle:Service')->isService($codeService)) {
              // Mise à jour du service
              $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
            } else {
              // Création du service
              $service = new Service();
              $service->setCodeService($codeService);
              $service->setExisteBaza(TRUE);
              $codeInconnu[$codeService] = TRUE;
            }
            $service->setLibService($lib);
            $service->setCodeSirh($codeSirh);
            $em->persist($service);
          }
        }
        $nbLine++;
      }
      fclose($csvFile);
      $em->flush();
      $output->writeln("OK");
    } else {
      $output->writeln("Fichier ".$fileName." introuvable");
    }

    foreach (array_keys($codeInconnu) as $code) {
      $erreur = new CtrlServiceInconnu;
      $erreur->setCodeService($code);
      $erreur->setRemarque("Code service trouvé dans GPARC, mais pas dans BAZA");
      $em->persist($erreur);
    }
    $em->flush();

    // Suppression des services non retrouvés dans BAZA
    $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->getServicesNonBaza();
    foreach($listeService as $service) {
      foreach ($service->getListeUtilisateurs() as $utilisateur) {
        $utilisateur->setServiceUtil(NULL);
        $em->persist($utilisateur);
      }
      $em->remove($service);
      $output->writeln("- suppression de ".$service->getCodeService());
    }
    unset($listeService);
    $em->flush();


    oci_free_statement($csr);
    unset($csr);

    $output->writeln("Import de ".$nb." services");

    $output->write("Création Direction et service inconnus... ");
    // Création d'une direction inconnue
    if (! $em->getRepository('EVPOS\affectationBundle\Entity\Direction')->isDirection("?")) {
      $dirInconnue = new Direction();
      $dirInconnue->setCodeDirection("?");
      $dirInconnue->setLibDirection("Direction inconnue");
      $em->persist($dirInconnue);
    }
    $em->flush();
    // Création d'un service inconnu
    if (! $em->getRepository('EVPOS\affectationBundle\Entity\Service')->isService("?")) {
      $servInconnu = new Service();
      $servInconnu->setCodeService("?");
      $servInconnu->setLibService("Service inconnu");
      $dirInconnue = $em->getRepository('EVPOS\affectationBundle\Entity\Direction')->getDirection("?");
      $servInconnu->setDirection($dirInconnue);
      $em->persist($servInconnu);
    }
    $em->flush();
    $output->writeln("OK");


    $output->writeln("*** Import des utilisateurs ***");
    // Marquage des utilisateurs existants
    $listeUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->findAll();
    foreach($listeUtil as $util) {
      $util->setExisteBaza(FALSE);
      $em->persist($util);
    }
    $em->flush();
    unset($listeUtil);

    $requeteBaza = "SELECT UPPER (u.ntuid) matricule,
                     u.ntufullnam nom,
                     u.code_service code_service,
                     ua.code_service code_service_sirh,
                     TO_CHAR (u.NTULASTLGN, 'YYYY-MM-DD') NTULASTLGN
                FROM baz_user_nt u left outer JOIN BAZ_AGENT ua on ua.matricule = u.ntuid
               WHERE ntuscript IS NOT NULL
                     AND (ntudisbld != 1 OR ntudisbld IS NULL)
                     AND ntufullnam IS NOT NULL
                     AND UPPER (ntuid) NOT LIKE '%\__' escape '\'";

    $csr = oci_parse ( $this->ORA , $requeteBaza) ;
    oci_execute ($csr) ;
    $nb = 0;

    while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
      $matUtil = $row["MATRICULE"];
      $nomUtil = utf8_encode($row["NOM"]);
      $codeService = strtoupper(utf8_encode($row["CODE_SERVICE"]));
      $codeSirh = strtoupper(utf8_encode($row["CODE_SERVICE_SIRH"]));
      $lastLogin = $row["NTULASTLGN"];

      if ($em->getRepository('EVPOSaffectationBundle:Utilisateur')->isUtilisateur($matUtil))
      $utilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
      else
      $utilisateur = new Utilisateur();

      $service = $em->getRepository('EVPOSaffectationBundle:Service')->getServiceSirh($codeSirh);
      if ($service === NULL) {
        // Service non trouvé dans SIRH
        $listeServiceRhErreur[$codeSirh] = TRUE;
        $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
      }
      if ($service !== NULL) {
        $utilisateur->setServiceUtil($service);
      } else {
        // Service non trouvé
        $listeServiceErreur[$codeService] = TRUE;
        $utilisateur->setServiceUtil(NULL);
        $output->writeln("Service non trouvé : ".$codeService."/".$codeSirh." pour utilisateur ".$matUtil);
      }

      if ($em->getRepository('EVPOSaffectationBundle:Service')->isService($codeService))
      $utilisateur->setServiceUtil($em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService));

      $utilisateur->setMatUtil($matUtil);
      $utilisateur->setNomUtil($nomUtil);
      if ($lastLogin !== null) {
        $utilisateur->setLastLogin(new \DateTime($lastLogin));
        $age = time() - $utilisateur->getLastLogin()->getTimestamp();
        $utilisateur->setAgeLogin(round($age/(60*60*24)));
      } else {
        $utilisateur->setLastLogin(new \DateTime("1/1/1900"));
        $utilisateur->setAgeLogin(9999);
      }
      $utilisateur->setExisteBaza(TRUE);

      $em->persist($utilisateur);

      $nb++;
    }
    $em->flush();

    oci_free_statement($csr);
    unset($csr);

    $output->writeln("Import de ".$nb." utilisateurs");

    $requeteBaza = "select distinct alias as mat_util, code_service from baz_exchange";
    $csr = oci_parse ( $this->ORA , $requeteBaza) ;
    oci_execute ($csr) ;
    $nb = 0;
    while (($row = oci_fetch_array($csr,OCI_ASSOC+OCI_RETURN_NULLS)) !== false) {
      $matUtil = $row["MAT_UTIL"];
      $codeService = $row["CODE_SERVICE"];
      $utilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($matUtil);
      if ($utilisateur !== NULL) {
        if ($utilisateur->getCodeService() != $codeService) {
          $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
          if ($service !== NULL) {
            $utilisateur->setServiceUtil($service);
            $nb++;
          }
        }
      }
    }
    $em->flush();
    oci_free_statement($csr);
    unset($csr);

    $output->writeln("Mise à jour du service de ".$nb." utilisateurs");


    // Suppression des utilisateurs qui n'existaient pas dans BAZA
    $output->write("Suppression des utilisateurs qui n'existaient pas dans BAZA... ");
    $listeUtil = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateursSuppr();
    foreach($listeUtil as $util) {
      $em->remove($util);
      $output->write($util->getNomUtil()." ");
    }
    $em->flush();
    unset($listeUtil);
    $output->writeln("OK");

    // Comptage des utilisateurs de chaque service
    $output->write("Comptage des utilisateurs de chaque service... ");
    $listeService = $em->getRepository('EVPOSaffectationBundle:Service')->findAll();
    foreach ($listeService as $service) {
      $nbUtil = $service->getListeUtilisateurs()->count();
      $service->setNbAgent($nbUtil);
      $em->persist($service);
    }
    unset($listeService);
    $em->flush();
    $output->writeln("OK");

    // Comptage des utilisateurs de chaque direction
    $output->write("Comptage des utilisateurs de chaque direction... ");
    $listeDirection = $em->getRepository('EVPOSaffectationBundle:Direction')->findAll();
    foreach ($listeDirection as $direction) {
      $nbUtil = 0;
      foreach ($direction->getListeServices() as $service) {
        $nbUtil += $service->getListeUtilisateurs()->count();
      }
      $direction->setNbAgent($nbUtil);
      $em->persist($direction);
    }
    unset($listeDirection);
    $em->flush();
    $output->writeln("OK");

    // Création des utilisateurs "libre service"
    $output->write("Création des utilisateurs libre service... ");
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
    $output->writeln("OK");

    // Mise à jour de la colonne "niveauVIP"
    $output->writeln('Mise à jour de la colonne "niveauVIP"...');
    $listeVIP = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getVIP();
    foreach ($listeVIP as $vip) {
      $vip->setNiveauVIP('');
      $em->persist($vip);
    }
    unset($listeVIP);

    $fileName = "/home/data/evpos/".$env."/gparc/util_vip.csv";
    $output->write("- Recherche du fichier ".$fileName."... ");
    if (file_exists($fileName)) {
      $output->writeln("OK");
      $csvFile = fopen($fileName, 'r');
      $nbLine = 0;
      while (($data = fgetcsv($csvFile, 0, ';')) !== FALSE) {
        if ($nbLine>0) {
          $mat = trim($data[0]);
          $niveauVIP = trim($data[1]);
          $output->write($mat.":".$niveauVIP.", ");

          $utilisateur = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->getUtilisateur($mat);
          if ($utilisateur !== NULL) {
            $utilisateur->setNiveauVIP($niveauVIP);
            $em->persist($utilisateur);
          }
        }
        $nbLine++;
      }
    } else {
      $output->writeln("Non trouvé");
    }
    $em->flush();
    fclose($csvFile);
    $output->writeln("OK");

    $output->writeln("Fin du traitement");

    oci_close ($this->ORA) ;
  }
}
