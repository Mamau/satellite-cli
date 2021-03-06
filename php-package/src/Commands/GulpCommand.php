<?php

declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelloWorldCommand
 * @package Mamau\Wkit\Commands
 */
class GulpCommand extends DockerAbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'run:gulp';

    /**
     * @var string
     */
    protected $imageWorkDir = '/home/node';

    /**
     * @var string
     */
    protected $imageName = 'mamau/gulp';

    /**
     * @var string
     */
    protected $imageHomeDir = '/home/node';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Run gulp command');
    }


    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runCommand($input->getArgument('cmd'));

        return self::SUCCESS;
    }
}
