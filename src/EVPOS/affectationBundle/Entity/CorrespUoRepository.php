<?php

namespace EVPOS\affectationBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CorrespUoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CorrespUoRepository extends EntityRepository {
  public function getCorrespUo($oldCodeUo, $newCodeUo) {
    $query = $this->createQueryBuilder('c')
      ->leftJoin('c.newUo', 'u')
      ->addSelect('c')
      ->addSelect('u')
      ->setParameter('oldCodeUo', $oldCodeUo)
      ->setParameter('newCodeUo', $newCodeUo)
      ->where('c.oldCodeUo = :oldCodeUo and u.codeUo = :newCodeUo')
      ->getQuery()
    ;
    return $query->getOneOrNullResult();
  }
}
