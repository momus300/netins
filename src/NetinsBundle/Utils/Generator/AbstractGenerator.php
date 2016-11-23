<?php

namespace NetinsBundle\Utils\Generator;

abstract class AbstractGenerator implements GeneratorInterface
{
    const FILES_PATH = __DIR__ . '/../../../../files/';

    /**
     * @param string $selectedFile
     * @return string
     */
    protected function getFilePath(string $selectedFile = ''):string
    {
        if (empty($selectedFile)) {
            $fileType = strtolower($this->getFileType());
            $fileName = self::FILES_PATH . $fileType . DIRECTORY_SEPARATOR;
            $fileName .= (new \DateTime())->format('Y-m-d_H:i:s') . '_' . uniqid() . '.' . $fileType;
        } else {
            $fileName = self::FILES_PATH . 'start/' . $selectedFile;
        }
        return $fileName;
    }

    abstract protected function getFileType():string;

    /**
     * @param $user
     * @return array
     */
    protected function getDataToArray($user):array
    {
        if (is_array($user)) {
            return $user;
        }

        return [
            'Number' => $user->getNumber(),
            'Name' => $user->getName(),
            'Surname' => $user->getSurname(),
            'Age' => $user->getAge()
        ];
    }
}