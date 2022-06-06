<?php

namespace App\Repository;

use App\Entity\Grilletarifaire;
use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Grilletarifaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grilletarifaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grilletarifaire[]    findAll()
 * @method Grilletarifaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrilletarifaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grilletarifaire::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Grilletarifaire $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Grilletarifaire $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Grilletarifaire[] Returns an array of Grilletarifaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function findOneByZoneandaount(Zone $zone,$value): ?Grilletarifaire
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.zone = :zone')
            ->andWhere('g.trancheA <= :val')
            ->andWhere('g.trancheB > :val')
            ->setParameter('zone', $zone)
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    /* */
}
