<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TirageRepository
 * @package AppBundle\Repository
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
     * @return int
     */
    public function getTotalCountBefore12Star()
    {
        return $this->createQueryBuilder('tirage')
            ->select('COUNT(tirage)')
            ->where('tirage.jour < \'2016-09-24\'')
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

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('t, jp')
            ->from('AppBundle:Tirage', 't')
            ->leftJoin('t.jokerPlus', 'jp')
            ->where('YEAR(t.jour) = :year')
            ->orderBy('t.jour', 'ASC')
            ->setParameters([
                'year' => $year
            ]);

        $query = $qb->getQuery();

        return $query->getResult();
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
  boule ASC
SQL;

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array
     */
    public function getStarsOrder()
    {
        $sql = <<<SQL
SELECT 
  etoile,
  COUNT(etoile) AS occurrence
FROM (
  SELECT etoile1 AS etoile FROM tirage UNION ALL
  SELECT etoile2 AS etoile FROM tirage
) AS union_table
GROUP BY etoile
ORDER BY 
  etoile ASC
SQL;

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array
     */
    public function getNumbersBestFriendsOrder()
    {
        $sql = '';
        for ($number1 = 1; $number1 <= 5; $number1++) {
            for ($number2 = 1; $number2 <= 5; $number2++) {
                if($number1 == $number2)
                    continue;

                $sql .= strlen($sql) > 1 ? ' UNION ' : '';
                $sql .= <<<SQL
(
  SELECT
  boule$number1 AS boule,
  boule$number2 AS duo,
  COUNT(*) AS occurrence
FROM tirage
GROUP BY
  boule$number1,
  boule$number2
)
SQL;
            }
        }

        $sql .= <<<SQLINVALID
ORDER BY
  boule ASC,
  duo ASC
SQLINVALID;

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    /**
     * @return array
     */
    public function getStarsBestFriendsOrder()
    {
        $sql = <<<SQL
(
  SELECT
  etoile1 AS etoile,
  etoile2 AS duo,
  COUNT(*) AS occurrence
FROM tirage
GROUP BY
  etoile1,
  etoile2
)
UNION
(
  SELECT
    etoile2 AS etoile,
    etoile1 AS duo,
   COUNT(*) AS occurrence
 FROM tirage
 GROUP BY
   etoile2,
   etoile1
)
ORDER BY
  etoile ASC,
  duo ASC
SQL;

        $statement = $this->getEntityManager()->getConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }
}
