<?php

namespace NetinsBundle\Utils\Generator;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class CSV extends AbstractGenerator
{
    /**
     * Zwraca dane
     *
     * @param string $fileName
     * @return array
     */
    public function getData(string $fileName)
    {
        $fileName = $this->getFilePath($fileName);

        if(!file_exists($fileName)){
            throw new FileNotFoundException($fileName);
        }

        if (($handle = fopen($fileName, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $users[] = [
                    'Number' => $data[0],
                    'Name' => $data[1],
                    'Surname' => $data[2],
                    'Age' => $data[3]
                ];
            }
            fclose($handle);
        }

        return $users;
    }

    /**
     * @return string
     */
    protected function getFileType():string
    {
        return 'csv';
    }

    /**
     * Tworzy pliki CSV
     *
     * @param array $users
     */
    public function generate(array $users)
    {
        $fileName = $this->getFilePath();
        $handle = fopen($fileName, 'w+');
        foreach ($users as $user) {
            fputcsv(
                $handle,
                $this->getDataToArray($user)
            );
        }
        fclose($handle);
    }

}