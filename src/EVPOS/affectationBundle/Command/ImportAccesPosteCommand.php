<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\CtrlPosteInconnu;
use EVPOS\affectationBundle\Entity\CtrlUoInconnue;

/**
 * Import des accès applicatifs à partir de la base GAP 
 * - GAP : accès aux applications
 */
class ImportAccesPosteCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_acces_poste')
            ->setDescription('Import des applications distribuées sur les postes')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		$em = $this->getContainer()->get('doctrine')->getManager();
		
		// Suppression des données existantes
        // ----------------------------------
        $output->write("Suppression de la liste des postes inconnus... ");
		$listePosteInconnu = $em->getRepository('EVPOSaffectationBundle:CtrlPosteInconnu')->findAll();
		foreach ($listePosteInconnu as $poste) {
			$em->remove($poste);
		}
		unset($listePosteInconnu);
		$output->writeln("OK");

		$output->write("Suppression de la liste des UO inconnues... ");
		$listeUoInconnue = $em->getRepository('EVPOSaffectationBundle:CtrlUoInconnue')->findAll();
		foreach ($listeUoInconnue as $uo) {
			$em->remove($uo);
		}
		unset($listeUoInconnue);
		$output->writeln("OK");
        
        $output->write("Suppression de la liste des UO liées aux postes... ");
		$listePoste = $em->getRepository('EVPOSaffectationBundle:Poste')->findAll();
		foreach ($listePoste as $tmpPoste) {
            $tmpPoste->delListeUo();
            $em->persist($tmpPoste);
		}
		unset($listePoste);
		$output->writeln("OK");

		$em->flush();
        // Fin des suppressions
        
        // Récupération des correspondances de code UO
        $output->writeln("Prise en compte des correspondances de code UO");
        $listeCorresp = $em->getRepository('EVPOSaffectationBundle:CorrespUo')->findAll();
        foreach($listeCorresp as $cor) {
            $correspCode[$cor->getOldCodeUo()] = $cor->getNewUo()->getCodeUo();
            //$output->writeln("- " . $cor->getOldCodeUo() . " -> " . $cor->getNewUo()->getCodeUo());
        }
        unset($listeCorresp);
        
        $output->write("Lecture du fichier des accès... ");
        $csvFile = __DIR__ . "/../../../../../LocalInstall/extraction-courant.csv";
        $fichierLicence = fopen($csvFile, "r");
		while($tab=fgetcsv($fichierLicence,1024,';')) {
            $champs = count($tab); 
			//affichage de chaque champ de la ligne en question
			if($champs = 2) {
				$codeUo = strtoupper(trim($tab[0]));
				$hostname = strtoupper(trim($tab[1]));
                
                // Prise en compte des correspondances de code UO
                if (array_key_exists($codeUo, $correspCode)) {
                    $codeUo = $correspCode[$codeUo];
                }
                
                // Enregistrement dans le tableau de correspondance
                $accesPosteUo[$hostname][] = $codeUo;
            }
        }
        $output->writeln("OK");
		
        $output->write("Ecriture des accès dans la base... ");
        foreach(array_keys($accesPosteUo) as $hostname) {
            // Recherche poste
            $poste = $em->getRepository('EVPOSaffectationBundle:Poste')->getPoste($hostname);
            //$output->writeln("Poste : " . $hostname);
            
            if ($poste === null) {
                // Enregistrement du poste non trouvé
                $posteInconnu = new CtrlPosteInconnu();
                $posteInconnu->setHostname($hostname);
                $em->persist($posteInconnu);
            }

            // Traitement des UO du poste
            $listeUo = array_unique($accesPosteUo[$hostname]);
            foreach ($listeUo as $codeUo) {
                // Recherche Uo
                $uo = $em->getRepository('EVPOSaffectationBundle:UO')->getUo($codeUo);
                //$output->writeln("- ".$codeUo);
                if ($uo === null) {
                    // Enregistrement de l'UO inconnue
                    $uoInconnue = new CtrlUoInconnue();
                    $uoInconnue->setCodeUo($codeUo);
                    $em->persist($uoInconnue);
                }
                
                // Enregistrement de la correspondance
                if ($uo !== null && $poste !== null) {
                    $poste->addListeUo($uo);
                    $em->persist($poste);
                }
                unset($uo);
            }
            unset($poste);
        }
        $output->writeln("OK");
		$em->flush();
		fclose($fichierLicence);
		$output->writeln("Fin du traitement");
	}
}
