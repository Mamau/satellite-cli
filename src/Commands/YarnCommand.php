<?php
declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class YarnCommand
 * @package Mamau\Wkit\Commands
 */
class YarnCommand extends DockerAbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'run:yarn';

    /**
     * @var string
     */
    protected $imageWorkDir = '/home/node';

    /**
     * @var string
     */
    protected $imageName = 'node';

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
        $this->addOption('ver', 'e', InputOption::VALUE_OPTIONAL, 'version of nodejs', '10')
            ->setDescription('Run yarn command');
    }


    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setVersion($input->getOption('ver'));
        $command = sprintf('/bin/bash -c "yarn %s"', $input->getArgument('cmd'));
        $this->runCommand($command);

        return self::SUCCESS;
    }

    /**
     * @param  string  $version
     */
    private function setVersion(string $version): void
    {
        $this->imageName = sprintf('%s:%s', $this->imageName, $version);
    }
}
