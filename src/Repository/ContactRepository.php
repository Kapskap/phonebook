<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Entity\Phone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 25;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function save(Contact $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Contact $entity, bool $flush = false): void
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
            'SELECT c
            FROM App\Entity\Contact c
            WHERE c.company LIKE :text
            ORDER BY c.company ASC'
        )->setParameter('text', '%'.$text.'%');

        // returns an array of Product objects
        return $query->getResult();
    }

    public function findAllSQL(string $text): array
    {
        $conn = $this->getEntityManager()->getConnection();
        $text="%".$text."%";

        $sql = '
            SELECT c.id, c.company, c.first_name, c.last_name, p.number, p.type_phone FROM contact c
                     LEFT JOIN phone p 
                     ON c.id=p.contact_id
            WHERE p.number like :text
            OR c.company like :text
            OR c.first_name like :text
            OR c.last_name like :text
            ORDER BY c.company ASC
            ';

        $resultSet = $conn->executeQuery($sql, ['text' => $text]);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
    }

    public function findAllWithSort(): array
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Contact c
            ORDER BY c.company ASC'
        );
        return $query->getResult();
    }

    public function getCommentPaginator(int $offset): Paginator
    {
        $query = $this->createQueryBuilder('c')
//            ->andWhere('c.contact = :contact')
//            ->setParameter('contact', $contact)
//            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->setFirstResult($offset)
            ->getQuery()
        ;

        return new Paginator($query);
    }

//    /**
//     * @return Contact[] Returns an array of Contact objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Contact
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
