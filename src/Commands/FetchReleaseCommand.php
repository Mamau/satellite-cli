<?php
declare(strict_types=1);

namespace Mamau\Wkit\Commands;

use Mamau\Wkit\Environment\OperatingSystem;
use Mamau\Wkit\Services\GithubService;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class FetchReleaseCommand
 * @package Mamau\Wkit\Commands
 */
class FetchReleaseCommand extends AbstractCommand
{
    protected static $defaultName = 'fetch';

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return int
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $os = OperatingSystem::getCurrentOSName();
        $this->io->writeln('');
        $this->io->writeln(sprintf('<info>Ваша операционная система: %s</info>', $os));
        $this->io->writeln('<info>Начало скачивания...</info>');
        $this->io->writeln('');

        $progress = new ProgressBar($output);
        $progress->setFormat('  [%bar%] %percent:3s%% (%size%Kb/%total%Kb)');
        $progress->setMessage('0.00', 'size');
        $progress->setMessage('?.??', 'total');
        $progress->display();

        (new GithubService())->fetchBinary($os, function (int $size, int $total) use ($progress) {
            if ($progress->getMaxSteps() !== $total) {
                $progress->setMaxSteps($total);
            }

            if ($progress->getStartTime() === 0) {
                $progress->start();
            }

            $progress->setMessage(\number_format($size / 1000, 2), 'size');
            $progress->setMessage(\number_format($total / 1000, 2), 'total');

            $progress->setProgress($size);
        });

        return self::SUCCESS;
    }
}
