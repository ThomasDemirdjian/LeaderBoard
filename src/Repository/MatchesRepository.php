<?php

namespace App\Repository;

use App\Entity\Matches;
use App\Entity\Teams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Matches|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matches|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matches[]    findAll()
 * @method Matches[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Matches::class);
    }

    /**
     * @param $teamId
     * @return Matches[] Returns an array of Matches objects
     */
    public function countByAllMatchesWon($teamId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(id)
            FROM App\Entity\Matches id
            WHERE (id.id_team1 = :id
            AND id.winner = 1)
            OR (id.id_team2 = :id
            AND id.winner = 2)'
        )->setParameter('id', $teamId);
        return $query->execute();
    }
    /**
     * @param $teamId
     * @return Matches[] Returns an array of Matches objects
     */
    public function countByAllMatchesEqual($teamId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(id)
            FROM App\Entity\Matches id
            WHERE (id.id_team1 = :id
            AND id.winner = 0)
            OR (id.id_team2 = :id
            AND id.winner = 0)'
        )->setParameter('id', $teamId);
        return $query->execute();
    }
    /**
     * @param $teamId
     * @return Matches[] Returns an array of Matches objects
     */
    public function countByAllMatchesLost($teamId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(id)
            FROM App\Entity\Matches id
            WHERE (id.id_team1 = :id
            AND id.winner = 2)
            OR (id.id_team2 = :id
            AND id.winner = 1)'
        )->setParameter('id', $teamId);
        return $query->execute();
    }
     /**
     * @param $teamId
     * @return Matches[] Returns an array of Matches objects
     */
    public function findAllMatches($teamId): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT id
            FROM App\Entity\Matches id
            WHERE (id.id_team1 = :id)
            OR (id.id_team2 = :id)'
        )->setParameter('id', $teamId);
        $result = $query->execute();
        return $result;
    }
    /*public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Matches
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
