<?php
namespace EVPOS\affectationBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EVPOS\affectationBundle\Entity\AccesUtilAppli;
use EVPOS\affectationBundle\Entity\AccesUtilUo;
use EVPOS\affectationBundle\Entity\Uo;
use EVPOS\affectationBundle\Entity\Poste;
use EVPOS\affectationBundle\Entity\PosteInconnu;
use EVPOS\affectationBundle\Entity\UoInconnue;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Import des acc�s applicatifs � partir de la base GAP 
 * - GAP : acc�s aux applications
 */
class ImportAccesPosteCommand extends ContainerAwareCommand
{   
    protected function configure() {
        parent::configure();
        $this
            ->setName('evpos:import_acces_poste')
            ->setDescription('Import des applications distribu�es sur les postes')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
		$em = $this->getContainer()->get('doctrine')->getManager();
		
		$output->write("Suppression de la liste des postes inconnus...");
		$listePosteInconnu = $em->getRepository('EVPOSaffectationBundle:PosteInconnu')->findAll();
		foreach ($listePosteInconnu as $poste) {
			$em->remove($poste);
		}
		unset($listePosteInconnu);
		$output->writeln("OK");

		$output->write("Suppression de la liste des UO inconnues...");
		$listeUoInconnue = $em->getRepository('EVPOSaffectationBundle:UoInconnue')->findAll();
		foreach ($listeUoInconnue as $uo) {
			$em->remove($uo);
		}
		unset($listeUoInconnue);
		$output->writeln("OK");
        
        $output->write("Suppression de la liste des UO li�es aux postes...");
		$listePoste = $em->getRepository('EVPOSaffectationBundle:Poste')->findAll();
		foreach ($listePoste as $tmpPoste) {
            $tmpPoste->delListeUo();
            $em->persist($tmpPoste);
		}
		unset($listePoste);
		$output->writeln("OK");

		$em->flush();
        
        // R�cup�ration des correspondances de code UO
        $output->writeln("Prise en compte des correspondances de code UO");
        $listeCorresp = $em->getRepository('EVPOSaffectationBundle:CorrespUo')->findAll();
        foreach($listeCorresp as $cor) {
            $correspCode[$cor->getOldCodeUo()] = $cor->getNewUo()->getCodeUo();
            $output->writeln("- " . $cor->getOldCodeUo() . " -> " . $cor->getNewUo()->getCodeUo());
        }
        unset($listeCorresp);
        
        $output->write("Lecture du fichier des acc�s...");
        $fichierLicence = fopen("/home/evpos/dev/LocalInstall/extraction-courant.csv", "r");
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
		
        $output->write("Ecriture des acc�s dans la base...");
        foreach(array_keys($accesPosteUo) as $hostname) {
            // Recherche poste
            $poste = $em->getRepository('EVPOSaffectationBundle:Poste')->getPoste($hostname);
            //$output->writeln("Poste : " . $hostname);
            
            if ($poste == null) {
                // Enregistrement du poste non trouv�
                $posteInconnu = new PosteInconnu();
                $posteInconnu->setHostname($hostname);
                $em->persist($posteInconnu);
            }

            // Traitement des UO du poste
            $listeUo = array_unique($accesPosteUo[$hostname]);
            foreach ($listeUo as $codeUo) {
                // Recherche Uo
                $uo = $em->getRepository('EVPOSaffectationBundle:UO')->getUo($codeUo);
                //$output->writeln("- ".$codeUo);
                if ($uo == null) {
                    // Enregistrement de l'UO inconnue
                    $uoInconnue = new UoInconnue();
                    $uoInconnue->setCodeUo($codeUo);
                    $em->persist($uoInconnue);
                }
                
                // Enregistrement de la correspondance
                if ($uo != null and $poste != null) {
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