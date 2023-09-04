<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }


    public function findBestSeries(int $popularity)
    {

        //EN DQL
        $dql = "SELECT s FROM App\Entity\Serie AS s
                WHERE s.popularity > 100
                AND s.vote > 8
                ORDER BY s.popularity DESC";

//        $em = $this->getEntityManager();
//        $query = $em->createQuery($dql);

        //QueryBuilder
        $qb = $this->createQueryBuilder('s');
        $qb
            ->addOrderBy("s.popularity", "DESC")
            ->addOrderBy("s.vote", "DESC")
            ->andWhere("s.popularity > :popularity")
            ->setParameter("popularity", $popularity)
            ->andWhere("s.vote > 8");

        $query = $qb->getQuery();

        $query->setMaxResults(50);
        return $query->getResult();
    }

    public function findSeriesWithPagination(int $page)
    {
        $limit = 50;

        $qb = $this->createQueryBuilder("s");
        $qb->addOrderBy("s.popularity", "DESC");
        $qb->setMaxResults($limit);
        $qb->leftJoin("s.seasons", "seasons");
        $qb->addSelect("seasons");

        $offset = $limit * ($page - 1);
        $qb->setFirstResult($offset);

        $query = $qb->getQuery();
        //gère le soucis de l'offset/limit quand jointure
        $paginator =  new Paginator($query);

        return $paginator;

    }


//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
