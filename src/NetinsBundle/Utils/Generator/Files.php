<?php

namespace NetinsBundle\Utils\Generator;

class Files implements FileInterface
{
    const XML_FILE = 'XML_STARTOWY.xml';
    const CSV_FILE = 'CSV_STARTOWY.csv';
    const FILE_PATH = __DIR__ . '/../../../../files/';
    const TYPE_FILES = ['xml', 'csv'];

    /**
     * Czyszczenie z utworzonych plikow
     */
    public function clear()
    {
        foreach (self::TYPE_FILES as $typeFile) {
            $files = glob(self::FILE_PATH . $typeFile . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * Zwraca tablice plikow
     *
     * @return array
     */
    public function getFiles():array
    {
        foreach (self::TYPE_FILES as $typeFile) {
            $dirPath = self::FILE_PATH . $typeFile;
            $files[$typeFile] = array_diff(scandir($dirPath), ['..', '.']);
        }

        return $files;
    }


}