<?php
declare(strict_types=1);

namespace Mamau\Satellite\Commands;

use Mamau\Satellite\Services\GithubService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class ConfigCommand
 * @package Mamau\Satellite\Commands
 */
class ConfigCommand extends AbstractCommand
{
    protected static $defaultName = 'config';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setDescription('Download fresh config satellite.yaml file');
    }

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
        (new GithubService())->fetchFileContent('satellite.yaml');

        return self::SUCCESS;
    }
}
