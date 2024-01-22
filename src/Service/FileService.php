<?php

namespace App\Service;

class FileService
{
    public function readFile(string $nameFile): array
    {
        $array = [];
        $file=fopen ($nameFile, "r");

        if ($file === NULL) {
            print 'błąd odczytu ';
        }
        else {
            $i = 0;
            while(!feof($file)) {
                $array[$i++] = fgets($file);
            }
        }

        fclose($file);

        return $array;
    }
}