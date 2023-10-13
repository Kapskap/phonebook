<?php

namespace App\Repository;

use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Phones>
 *
 * @method Phones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phones[]    findAll()
 * @method Phones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhonesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Phone::class);
    }

    public function save(Phone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Phone $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    /**
     * @return Phone[]
     */
    public function findAllThanText(string $text): array
    {
        $entityManager = $this->getEntityManager();
  //      WHERE p.company = :text
        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Phone p
            WHERE p.company LIKE :text
            ORDER BY p.company ASC'
        )->setParameter('text', '%'.$text.'%');

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findAllSQL(string $text): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $text="%".$text."%";

        $sql = '
            SELECT * FROM phone p
            WHERE p.number like :text
            OR p.company like :text
            OR p.first_name like :text
            OR p.last_name like :text
            ORDER BY p.company ASC
            ';

        $resultSet = $conn->executeQuery($sql, ['text' => $text]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }
//    /**
//     * @return Phone[] Returns an array of Phones objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Phones
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
