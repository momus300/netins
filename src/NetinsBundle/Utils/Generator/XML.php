<?php

namespace NetinsBundle\Utils\Generator;

use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class XML extends AbstractGenerator
{
    /**
     * @param string $fileName
     * @return mixed
     */
    public function getData(string $fileName)
    {
        $fileName = $this->getFilePath($fileName);

        if(!file_exists($fileName)){
            throw new FileNotFoundException($fileName);
        }

        $xml = simplexml_load_file($fileName);
        $array = json_decode(json_encode((array)$xml), true);
        return $array['user'];
    }

    /**
     * @return string
     */
    protected function getFileType():string
    {
        return 'xml';
    }

    /**
     * @param array $users
     */
    public function generate(array $users)
    {
        $xml = new \SimpleXMLElement('<root/>');

        foreach ($users as $user) {
            $xml_user = $xml->addChild('user');
            $usersArray = $this->getDataToArray($user);
            $usersArray = array_flip($usersArray);
            array_walk_recursive($usersArray, [$xml_user, 'addChild']);
        }

        $fileName = $this->getFilePath();

        $xml->saveXML($fileName);
    }

}