<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Serie[] findByTitle($title)
 */
class SerieRepository extends ServiceEntityRepository
{

    private Security $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Serie::class);
        $this->security = $security;
    }

    public function add(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries(int $page)
    {

       // $this->security->getUser();

        $limit = 50;

        //En DQL
//        $entityManager = $this->getEntityManager();
//        $dql = "SELECT s FROM App\Entity\Serie s
//                WHERE s.vote > 8
//                AND s.popularity > 100
//                ORDER BY s.popularity DESC";
//
//        $query = $entityManager->createQuery($dql);

        //calcul de l'offset et de limit en fonction du numéro de page
        $offset = ($page - 1) * $limit;

        //En queryBuilder
        //select
        $qb = $this->createQueryBuilder('s');
        $qb->leftJoin('s.seasons', 'seasons');
        $qb->addSelect('seasons');
        //order by
        $qb->addOrderBy('s.popularity', 'DESC');
        //$qb->andWhere("s.vote > :vote")->setParameter("vote", $vote);

        $query = $qb->getQuery();

        //pareil pour DQL et QB
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);

        $paginator = new Paginator($query);
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
