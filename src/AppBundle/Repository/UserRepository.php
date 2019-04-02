<?php

namespace AppBundle\Repository;

/**
 * UserRepository.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll($enabledOnly = true)
    {
        return $this->findAllQb($enabledOnly)->getQuery()->getResult();
    }

    public function findAllQb($enabledOnly = true)
    {
        $qb = $this->createQueryBuilder('u')
                ->orderBy('u.lastname', 'ASC');
        
        if ($enabledOnly) {
            $qb->andWhere('u.enabled = 1');
        }
        return $qb;
    }
}
