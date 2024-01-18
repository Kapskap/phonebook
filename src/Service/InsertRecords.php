<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class InsertRecords
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function insertContact(string $first_name, string $last_name, string $company, \DateTimeImmutable $created_at)
    {
        $em = $this->entityManager;

        $query = "INSERT INTO contact (first_name, last_name, company, created_at)
                    VALUES(:first_name, :last_name, :company, :created_at)";
        $stmt = $em->getConnection()->prepare($query);
        $r = $stmt->execute(array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'company' => $company,
            'created_at' => $created_at->getTimestamp(),
        ));
    }
}

