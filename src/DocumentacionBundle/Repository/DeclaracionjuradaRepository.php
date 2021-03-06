<?php

namespace Jubilaciones\DeclaracionesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * DeclaracionjuradaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeclaracionjuradaRepository extends EntityRepository {

    public function findAllDeclaracionesPorPeriodo() {
        $em = $this->getEntityManager();
        $dql = "SELECT d
                FROM JubilacionesDeclaracionesBundle:Declaracionjurada d
                ORDER BY d.periodoAnio ASC, d.periodoMes ASC ";
        return $em->createQuery($dql)->getResult();
    }


}
