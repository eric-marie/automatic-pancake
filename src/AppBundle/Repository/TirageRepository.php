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

    /**
     * @param int $year
     * @return array
     */
    public function findByYear($year)
    {
        $emConfig = $this->getEntityManager()->getConfiguration();
        $emConfig->addCustomDatetimeFunction('YEAR', 'DoctrineExtensions\Query\Mysql\Year');

        $dql = <<<DQL
SELECT t 
FROM AppBundle:Tirage t
WHERE YEAR(t.jour) = :year
ORDER BY t.jour ASC
DQL;
        return $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameters([
                'year' => $year
            ])
            ->getResult();
    }
}
