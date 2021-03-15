<?php
declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class DockerAbstractCommand
 * @package Mamau\Wkit\Commands
 */
abstract class DockerAbstractCommand extends Command
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
     * @var string
     */
    protected $imageWorkDir = '/home';

    /**
     * @var string
     */
    protected $imageHomeDir = '/home';

    /**
     * @var string
     */
    protected $imageName;

    /**
     * @var SymfonyStyle
     */
    protected $io;

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addArgument('cmd', InputArgument::OPTIONAL, 'Command for run image', '--version')
            ->setDescription('Run command');
    }

    /**
     * @param  string  $command
     * @return string
     */
    private function collectCommand(string $command): string
    {
        return implode(' ', [
            $this->head,
            $this->action,
            '-ti',
            $this->getCurrentUserId(),
            $this->imageWorkDir(),
            $this->imageHomeDirVolume(),
            $this->imageAddingArguments(),
            $this->imageName(),
            $command,
        ]);
    }

    /**
     * @return string
     */
    protected function getCurrentUserId(): string
    {
        return sprintf('-u %d', getmyuid());
    }

    /**
     * @return array
     */
    protected function imageAddingArguments(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function imageHomeDirVolume(): string
    {
        return sprintf('-v %s:%s', getcwd(), $this->imageHomeDir);
    }

    /**
     * @return string
     */
    protected function imageName(): string
    {
        return $this->imageName;
    }

    /**
     * @return string
     */
    protected function imageWorkDir(): string
    {
        return sprintf('--workdir=%s', $this->imageWorkDir);
    }

    /**
     * @param  string  $cmd
     */
    protected function runCommand(string $cmd): void
    {
        $command = $this->collectCommand($cmd);

        $this->io->info(sprintf('Run command: %s', $command));
        $this->io->text(shell_exec($command));
    }
}
