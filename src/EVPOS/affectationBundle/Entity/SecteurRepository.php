<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SecteurRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SecteurRepository extends EntityRepository {
  /**
   * Récupération d'un service à partir de son code
   */
  public function getSecteur($codeSecteur) {
      $query = $this->createQueryBuilder('s')
          ->setParameter('code', $codeSecteur)
          ->where('s.codeSecteur = :code')
          ->getQuery()
      ;

      return $query->getOneOrNullResult();
  }
}