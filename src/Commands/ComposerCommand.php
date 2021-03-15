<?php

declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ComposerCommand
 * @package Mamau\Wkit\Commands
 */
class ComposerCommand extends DockerAbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'run:composer';

    /**
     * @var string
     */
    protected $imageWorkDir = '/home/www-data';

    /**
     * @var string
     */
    protected $imageName = 'composer';

    /**
     * @var string
     */
    protected $imageHomeDir = '/home/www-data';

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();
        $this->addOption('ver', 'e', InputOption::VALUE_OPTIONAL, 'version of composer', '2')
            ->setDescription('Run composer command');
    }


    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setVersion($input->getOption('ver'));
        //todo: add config
//        composer config $(composer-repository) $(username) $(token)
        $command = sprintf('/bin/bash -c "composer %s --ignore-platform-reqs"', $input->getArgument('cmd'));
        $this->runCommand($command);

        return self::SUCCESS;
    }

    /**
     * @return string
     */
    protected function imageAddingArguments(): string
    {
        return sprintf('-v %s/cache/composer:/tmp -v /etc/ssl/certs:/etc/ssl/certs', getcwd());
    }

    /**
     * @param  string  $version
     */
    private function setVersion(string $version): void
    {
        $this->imageName = sprintf('%s:%s', $this->imageName, $version);
    }
}
