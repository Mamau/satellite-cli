<?php
declare(strict_types=1);

namespace Mamau\Wkit\Services;

use Mamau\Wkit\Environment\OperatingSystem;
use Mamau\Wkit\Repositories\GithubRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class GithubService
 * @package Mamau\Wkit\Services
 */
final class GithubService
{
    /**
     * @var GithubRepository
     */
    private $repository;

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * GithubService constructor.
     */
    public function __construct()
    {
        $this->repository = new GithubRepository();
        $this->client = HttpClient::create();
    }

    /**
     * @param  string  $fileName
     * @param  \Closure|null  $progress
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchFileContent(string $fileName, \Closure $progress = null): void
    {
        $fileContent = $this->repository->fetchFileContent($fileName);
        $this->download($fileContent->getDownloadUrl(), $fileContent->getName(), $progress);
    }

    /**
     * @param  string  $os
     * @param  \Closure|null  $progress
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchBinary(string $os, \Closure $progress = null): void
    {
        $assets = $this->repository->fetchReleases();

        foreach ($assets as $asset) {
            if (str_contains($asset->getName(), $os)) {
                $name = 'starter';
                if ($os === OperatingSystem::OS_WINDOWS) {
                    $name = $name.'.exe';
                }
                $this->download($asset->getUri(), $name, $progress);
            }
        }
    }

    /**
     * @param  string  $url
     * @param  string  $name
     * @param  \Closure|null  $progress
     * @throws TransportExceptionInterface
     */
    private function download(string $url, string $name, \Closure $progress = null): void
    {
        $file = new \SplFileObject(PROJECT_ROOT.$name, 'wb+');

        foreach ($this->fetchContent($url, $progress) as $chunk) {
            $file->fwrite($chunk);
        }
    }

    /**
     * @param  string  $url
     * @param  \Closure|null  $progress
     * @return \Traversable
     * @throws TransportExceptionInterface
     */
    private function fetchContent(string $url, \Closure $progress = null): \Traversable
    {
        $response = $this->client->request('GET', $url, [
                'on_progress' => $progress,
            ]
        );

        foreach ($this->client->stream($response) as $chunk) {
            yield $chunk->getContent();
        }
    }
}
