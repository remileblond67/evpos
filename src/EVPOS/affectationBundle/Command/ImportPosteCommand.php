<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\Poste;

/**
 * Import des postes à partir de GPARC
 */
class ImportPosteCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_poste')
            ->setDescription('Import des postes depuis la base GPARC')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		$em = $this->getContainer()->get('doctrine')->getManager();
        
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
        
        while (($data = fgetcsv($csvFile, 0, ';')) !== FALSE) {
            if ($nbLine>0) {
                $hostname = strtoupper(trim($data[3]));   
                if ($hostname != "-") {
                    // $output->writeln($nbLine . " " . $hostname);
                    $codeMateriel = $data[2];
                    $statut = $data[4];
                    $modele = $data[6];
                    $categorie = $data[7];
                    if ($data[8] == "CMD licence windows 8") {
                        $licenceW8 = $data[9];
                    } else {
                        $licenceW8 = "NON";
                    }
                    $localisation = $data[11];
                    $matUtil = $data[12];
                    $commentaire = $data[14];
                    $typeUsage = $data[15];
                                    
                    if ($hostname !== "") {
                        // Recherche si le poste existe
                        $poste = $em->getRepository('EVPOSaffectationBundle:Poste')->find($hostname);
                        if ($poste === NULL) {
                            // Création d'un nouveau poste
                            $poste = new Poste();
                            $poste->setHostname($hostname);
                            
                            $output->writeln("Nouveau poste : ".$hostname);
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
                        switch ($licenceW8) {
                            case "OUI":
                                $poste->setLicenceW8(TRUE);
                                break;
                            default:
                                $poste->setLicenceW8(FALSE);
                        }
                        
                        // Recherche de l'utilisateur
                        $util = $em->getRepository('EVPOSaffectationBundle:Utilisateur')->find($matUtil);
                        if ($util !== NULL) {
                            $poste->addListeUtilisateur($util);
                        }
                        
                        $em->persist($poste);
                    }
                }
            }
            $nbLine++;
        }
        fclose($csvFile);
        
        $output->writeln("OK (".$nbLine." lignes)");
        $output->write("Commit... ");
        $em->flush();
        $output->writeln("OK");
        
        // Suppression des postes ne figurant pas dans la liste extraite de GPARC
        $output->write("Suppression des postes inexistants... ");
        $listePoste = $em->getRepository('EVPOSaffectationBundle:Poste')->findNonGparc();
        foreach ($listePoste as $poste) {
            $output->writeln("Suppression du poste ".$poste->getHostname());
            $em->remove($poste);
        }
        $em->flush();
        $output->writeln("OK");

		$output->writeln("Fin du traitement");
	}
}
