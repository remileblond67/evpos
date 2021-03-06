<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * AccesServiceAppliRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AccesServiceAppliRepository extends EntityRepository
{
    public function getListeAccesServiceAppli() {
        $query = $this->createQueryBuilder('a')
            ->getQuery()
        ;

        return $query->getResult();
    }
    
    
    /**
     * Recherche si l'accès existe déja
     */
    public function isAccesServiceAppli($application, $service) {
        $nb = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->setParameter('appli', $application)
            ->setParameter('service', $service)
            ->where('a.appliAcces = :appli and a.serviceAcces = :service')
            ->getQuery()
            ->getSingleScalarResult()
        ;
        
        if ($nb >= 1) {
            $retour = true;
        } else {
            $retour = false;
        }
        
        return $retour;
    }
    
    /**
     * Recherche un accès à partir des identifiants
     */
    public function getAccesServiceAppli($application, $service) {
        $acces = $this->createQueryBuilder('a')
            ->select('a')
            ->setParameter('appli', $application)
            ->setParameter('service', $service)
            ->where('a.appliAcces = :appli and a.serviceAcces = :service')
            ->getQuery()
            ->getSingleResult()
        ;
            
        return $acces;
    }
    
    /**
     * Retourne le nombre d'acces applicatifs
     * Utilisé pour les indicateurs d'avancement
     */
    public function getNbAccesServiceAppli() {
        $query = $this->createQueryBuilder('a')
            ->select('count(a)')
            ->getQuery()
        ;

        return $query->getSingleScalarResult();
    }
}
