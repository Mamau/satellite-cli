<?php
declare(strict_types=1);

namespace Mamau\Satellite\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class AbstractCommand
 * @package Mamau\Satellite\Commands
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var SymfonyStyle
     */
    protected SymfonyStyle $io;

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }
}
