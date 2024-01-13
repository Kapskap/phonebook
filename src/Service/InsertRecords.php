<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class InsertRecords
{
    public function insertContact(string $first_name, string $last_name, string $company, EntityManagerInterface $entityManager)
    {
        $query = "INSERT INTO contact (first_name, last_name, company)
                    VALUES(:first_name, :last_name, :company)";
        $stmt = $entityManager->getConnection()->prepare($query);
        $r = $stmt->execute(array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'company' => $company,
            // 'created_at' => $row['created_at'],
        ));
    }
}

