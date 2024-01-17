<?php

namespace App\Service;

class ReadFile
{
    public function readTxt(string $nameFile): array
    {
        $array = [];
        $file=fopen ($nameFile, "r");
        if (!($file))
        {
            print 'błąd odczytu ';
        }
        else {
            $i = 0;
            while(!feof($file)) {
                $array[$i++] = fgets($file);
            }
        }
        fclose($file);
        if ($array) return $array;
        else return 0;
    }
}