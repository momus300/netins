<?php

namespace NetinsBundle\Utils\Generator;

/**
 * Class Generator
 * @package NetinsBundle\Utils
 */
class Generator
{
    private $generators;

    /**
     * @param GeneratorInterface $generator
     */
    public function addGenerator(GeneratorInterface $generator)
    {
        $this->generators[] = $generator;
    }

    /**
     * @param array $users
     */
    public function generate(array $users)
    {
        foreach ($this->generators as $generator) {
            $generator->generate($users);
        }
    }
}