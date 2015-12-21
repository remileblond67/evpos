<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Poste;
use EVPOS\affectationBundle\Entity\Equipement;
use EVPOS\affectationBundle\Entity\CtrlUtilisateurInconnu;

/**
 * Import des postes à partir de GPARC
 */
class ImportPosteCommand extends ContainerAwareCommand
{
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_poste')
            ->setDescription('Import des postes et de leurs équipements depuis la base GPARC')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
		$em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln("*** Import des postes ***");

        // Préparation des données
        $output->write("Préparation des données existantes... ");
        $listePoste = $em->getRepository('EVPOSaffectationBundle:Poste')->findAll();
        foreach($listePoste as $poste) {
            $poste->setLicenceW8(FALSE);
            $poste->delListeUtilisateurs();
            $poste->setExisteGparc(FALSE);
        }
        $em->flush();
        unset($listePoste);
        $output->writeln("OK");

        // Lecture du fichier CSV extrait de GPARC
        $output->write("Lecture du fichier des postes... ");
        $fileName = "/home/data/evpos/dev/gparc/materiel.csv";
        $csvFile = fopen($fileName, 'r');
        $nbLine = 0;

        $serviceInconnu = $em->getRepository('EVPOSaffectationBundle:Service')->getService("?");

        $listeUtilisateurInconnu = [];

        while (($data = fgetcsv($csvFile, 0, ';')) !== FALSE) {
            if ($nbLine>0) {
                $hostname = trim(strtoupper($data[3]));
                if ($hostname != "-") {
                    // $output->writeln($nbLine . " " . $hostname);
                    $codeService = $data[1];
                    $numService = $data[13];
                    $codeMateriel = $data[2];
                    $statut = $data[4];
                    $modele = $data[6];
                    $categorie = $data[7];
                    if ($data[8] == "CMD licence windows 8") {
                        $licenceW8 = $data[9];
                    } else {
                        $licenceW8 = "NON";
                    }
                    if ($data[10] == "SSD") {
                        $ssd = $data[11];
                    } else {
                        $ssd = "NON";
                    }
                    $localisation = $data[14];
                    $matUtil = strtoupper($data[15]);
                    $commentaire = $data[17];
                    $typeUsage = $data[18];
                    if ($data[23] == "Type Réseau") {
                        $typeReseau = $data[24];
                    } else {
                        $typeReseau = NULL;
                    }

                    if ($hostname !== "") {
                        // Recherche si le poste existe
                        $poste = $em->getRepository('EVPOSaffectationBundle:Poste')->find($hostname);
                        if ($poste === NULL) {
                            // Création d'un nouveau poste
                            $poste = new Poste();
                            $poste->setHostname($hostname);
                        }

                        // Update des caractéristiques du poste
                        $poste->setCodeMateriel($codeMateriel);
                        $poste->setCategorie($categorie);
                        $poste->setStatut($statut);
                        $poste->setModele($modele);
                        $poste->setCategorie($categorie);
                        $poste->setLocalisation($localisation);
                        $poste->setCommentaire($commentaire);
                        $poste->setTypeUsage($typeUsage);
                        $poste->setExisteGparc(TRUE);
                        $poste->setTypeReseau($typeReseau);
                        switch ($licenceW8) {
                            case "OUI":
                                $poste->setLicenceW8(TRUE);
                                break;
                            default:
                                $poste->setLicenceW8(FALSE);
                        }
                        switch ($ssd) {
                            case "OUI":
                                $poste->setSSD(TRUE);
                                break;
                            default:
                                $poste->setSSD(FALSE);
                        }
                        // Mise à jour du service
                        $service = $em->getRepository('EVPOSaffectationBundle:Service')->getService($codeService);
                        if ($service !== NULL) {
                            $poste->setService($service);
                        } else {
                            $poste->setService($serviceInconnu);
                        }

                        // Recherche de l'utilisateur
                        $util = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->find($matUtil);
                        if ($util === NULL & $service !== NULL) {
                            $util = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->find("LS_".$service->getCodeService());
                        }
                        if ($util !== NULL) {
                            $poste->addListeUtilisateur($util);
                        } else {
                            $listeUtilisateurInconnu[] = $matUtil;
                        }
                        $em->persist($poste);
                    }
                }
            }
            $nbLine++;
        }
        fclose($csvFile);
        $em->flush();
        $output->writeln("OK (".$nbLine." lignes)");

        $output->writeln("*** Affectation des postes sans service au service de leur Utilisateur  ***");
        $postesSansService = $em->getRepository('EVPOSaffectationBundle:Poste')->getPostesSansService();
        foreach($postesSansService as $poste) {
            foreach ($poste->getListeUtilisateurs() as $user) {
                $service = $user->getServiceUtil();
                $poste->setService($service);
                $output->write('_');
            }
            $em->persist($poste);
            $output->write('.');
        }
    		$output->writeln("OK");
        unset($postesSansService);
        $output->writeln("Fin de report");
        $em->flush();
        $output->writeln("Validation des modifications");

        // Enregistrement des utilisateurs inconnus
        $output->write("Enregistrement des erreurs... ");
        $listeErreur = $em->getRepository('EVPOS\affectationBundle\Entity\CtrlUtilisateurInconnu')->findAll();
        foreach ($listeErreur as $erreur) {
            $em->remove($erreur);
        }
        $em->flush();
        unset($listeErreur);
        foreach (array_unique($listeUtilisateurInconnu) as $matErreur) {
            $erreur = new CtrlUtilisateurInconnu();
            $erreur->setMatUtil($matErreur);
            $erreur->setCommentaire("Matricule trouvé lors de l'import des postes GPARC, mais pas dans BAZA.");
            $em->persist($erreur);
        }
        $em->flush();
        unset($listeUtilisateurInconnu);
        $output->writeln("OK");

        // Suppression des postes ne figurant pas dans la liste extraite de GPARC
        $output->write("Suppression des postes non référencés dans GPARC... ");
        $listePoste = $em->getRepository('EVPOSaffectationBundle:Poste')->findNonGparc();
        foreach ($listePoste as $poste) {
            $output->writeln("Suppression du poste ".$poste->getHostname());
            $em->remove($poste);
        }
        $em->flush();
        unset($listePoste);
        $output->writeln("OK");

        // Import des équipements liés
        $output->writeln("*** Import des équipements liés ***");
        $output->write("Suppression des équipements existants... ");
        $listeEquipement = $em->getRepository('EVPOSaffectationBundle:Equipement')->findAll();
        foreach($listeEquipement as $equipement) {
            $em->remove($equipement);
        }
        $em->flush();
        unset($listeEquipement);
        $output->writeln("OK");

        // Lecture du fichier CSV extrait de GPARC
        $output->write("Lecture du fichier des équipements liés... ");
        $fileName = [];
        $fileName[] = "/home/data/evpos/dev/gparc/materiel-lie.csv";
        $fileName[] = "/home/data/evpos/dev/gparc/materiel-lie-montant.csv";

        $listeCodeMateriel = [];

        foreach ($fileName as $file) {
            $output->write($file." ");
            $csvFile = fopen($file, 'r');
            $nbLine = 0;
            $nbDoublon = 0;

            while (($data = fgetcsv($csvFile, 0, ';')) !== FALSE) {
                if ($nbLine > 0) {
                    $hostname = strtoupper(trim($data[0]));
                    if ($hostname != "") {
                        $codeMateriel = strtoupper(trim($data[1]));
                        if (in_array($codeMateriel, $listeCodeMateriel)) {
                            // Le code a déjà été rencontré
                            $nbDoublon++;
                        } else {
                            // Le code matériel n'avais jamais été rencontré
                            $listeCodeMateriel[] = $codeMateriel;

                            $categorie = trim($data[2]);
                            $modele = trim($data[3]);

                            $equipement = new Equipement();
                            $equipement->setCodeMateriel($codeMateriel);
                            $equipement->setCategorie($categorie);
                            $equipement->setModele($modele);

                            // recherche du poste lié
                            $poste = $em->getRepository('EVPOS\affectationBundle\Entity\Poste')->find($hostname);
                            if ($poste !== NULL) {
                                $equipement->setPoste($poste);
                                $em->persist($equipement);
                            }
                        }
                    }
                }
                $nbLine++;
            }
            fclose($csvFile);
        }
        unset($listeCodeMateriel);

        $em->flush();
        $output->writeln("OK (".$nbDoublon." doublons)");

        $output->write("Mise à jour du nombre de postes par service... ");
        $listeService = $em->getRepository('EVPOS\affectationBundle\Entity\Service')->findAll();
        foreach ($listeService as $service) {
          $service->setNbPoste($service->getListePostes()->count());
          $em->persist($service);
        }
        unset($listeService);
        $em->flush();
        $output->writeln("OK");

        $output->write("Mise à jour du nombre de poste par direction... ");
        $listeDirection = $em->getRepository('EVPOS\affectationBundle\Entity\direction')->findAll();
        foreach ($listeDirection as $direction) {
          $nbPoste = 0;
          foreach ($direction->getListeServices() as $service) {
            $nbPoste += $service->getNbPoste();
          }
          $direction->setNbPoste($nbPoste);
          $em->persist($direction);
        }
        unset($listeDirection);
        $em->flush();
        $output->writeln("OK");

		$output->writeln("Fin du traitement");
	}
}
