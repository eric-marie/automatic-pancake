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

    /**
     * @return array
     */
    public function getNumbersOrder()
    {
        $sql = <<<SQL
SELECT 
  boule,
  COUNT(boule) AS occurrence
FROM (
  SELECT boule1 AS boule FROM tirage UNION ALL
  SELECT boule2 AS boule FROM tirage UNION ALL
  SELECT boule3 AS boule FROM tirage UNION ALL
  SELECT boule4 AS boule FROM tirage UNION ALL
  SELECT boule5 AS boule FROM tirage
) AS union_table
GROUP BY boule
ORDER BY 
  occurrence DESC,
  boule ASC
SQL;

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @param array $numbers
     * @return array
     */
    public function getNumbersBestFriendsOrder($numbers = [])
    {
        if (0 == count($numbers)) return [];

        $whereClauseUnion = '';
        foreach ($numbers as $number) {
            if (!is_numeric($number)) return [];

            $whereClauseUnion .= '' == $whereClauseUnion ? 'WHERE ' : ' AND ';
            $whereClauseUnion .= $number . ' IN (boule1, boule2, boule3, boule4, boule5)';
        }
        $whereClauseAll = implode(', ', $numbers);

        $sql = <<<SQL
SELECT 
  boule,
  COUNT(boule) AS occurrence
FROM (
  SELECT boule1 AS boule FROM tirage $whereClauseUnion UNION ALL
  SELECT boule2 AS boule FROM tirage $whereClauseUnion UNION ALL
  SELECT boule3 AS boule FROM tirage $whereClauseUnion UNION ALL
  SELECT boule4 AS boule FROM tirage $whereClauseUnion UNION ALL
  SELECT boule5 AS boule FROM tirage $whereClauseUnion
) AS union_table
WHERE
  boule NOT IN ($whereClauseAll)
GROUP BY boule
ORDER BY 
  occurrence DESC,
  boule ASC
SQL;

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }
}
