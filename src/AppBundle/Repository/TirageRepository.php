<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TirageRepository
 * @package AppBundle\Entity
 */
class TirageRepository extends EntityRepository
{
    /**
     * @return int
     */
    public function getTotalCount()
    {
        return $this->createQueryBuilder('tirage')
            ->select('COUNT(tirage)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
