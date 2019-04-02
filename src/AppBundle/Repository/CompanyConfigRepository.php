<?php

namespace AppBundle\Repository;

/**
 * CompanyConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CompanyConfigRepository extends \Doctrine\ORM\EntityRepository
{
    
    public function findCurrentConfig()
    {
        return $this->createQueryBuilder('c')
                ->andWhere('c.active = 1')
                ->orderBy('c.date', 'DESC')
                ->setMaxResults(1)
                ->getQuery()->getOneOrNullResult();
    }
}