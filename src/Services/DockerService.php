<?php
declare(strict_types=1);

namespace Mamau\Wkit\Services;

/**
 * Class DockerService
 * @package Mamau\Wkit\Services
 */
class DockerService
{
    /**
     * @var string
     */
    private $head = 'docker';

    /**
     * @var string
     */
    private $action = 'run';

    /**
     * DockerService constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return string
     */
    public function collectCommand(): string
    {
        return implode(' ', $this->command());
    }

    /**
     * @return array
     */
    private function command(): array
    {
        return [
            $this->head,
            $this->action,
            '-ti',
            '-u',
            getmyuid(),
            '-v',
            getcwd()
//            -v $(work-dir):/home/node
        ];
    }

}
