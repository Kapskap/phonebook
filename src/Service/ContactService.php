<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function insertContact(string $firstName, string $lastName, string $company, \DateTimeImmutable $createdAt)
    {
        $em = $this->entityManager;

        $query = "INSERT INTO contact (first_name, last_name, company, created_at)
                    VALUES(:first_name, :last_name, :company, :created_at)";

        $stmt = $em->getConnection()->prepare($query);
        $r = $stmt->execute(array(
            'first_name' => $firstName,
            'last_name' => $lastName,
            'company' => $company,
            'created_at' => $createdAt->format('Y-m-d H:i:s'),
        ));
    }
}

