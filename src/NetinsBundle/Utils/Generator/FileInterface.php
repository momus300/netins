<?php

namespace NetinsBundle\Utils\Generator;

interface FileInterface
{
    public function clear();

    public function getFiles():array;
}