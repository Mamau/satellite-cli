<?php
declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BowerCommand
 * @package Mamau\Wkit\Commands
 */
class BowerCommand extends DockerAbstractCommand
{
    /**
     * @var string
     */
    protected static $defaultName = 'run:bower';

    /**
     * @var string
     */
    protected $imageWorkDir = '/home/node';

    /**
     * @var string
     */
    protected $imageName = 'mamau/bower';

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
        $this->setDescription('Run bower command');
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
