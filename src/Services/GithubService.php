<?php

declare(strict_types=1);

namespace Mamau\Satellite\Services;

use Mamau\Satellite\Environment\OperatingSystem;
use Mamau\Satellite\Repositories\GithubRepository;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class GithubService
 * @package Mamau\Satellite\Services
 */
final class GithubService
{
    /**
     * @var GithubRepository
     */
    private GithubRepository $repository;

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * GithubService constructor.
     */
    public function __construct()
    {
        $this->repository = new GithubRepository();
        $this->client = HttpClient::create();
    }

    /**
     * @param string $fileName
     * @param \Closure|null $progress
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
     * @param string $os
     * @param \Closure|null $progress
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
            if (strpos($asset->getName(), $os) === false) {
                continue;
            }

            $name = 'satlt';
            if ($os === OperatingSystem::OS_WINDOWS) {
                $name .= '.exe';
            }

            $this->download($asset->getUri(), $name, $progress);
            break;
        }
    }

    /**
     * @param string $url
     * @param string $name
     * @param \Closure|null $progress
     * @param string|null $savePath
     * @return void
     * @throws TransportExceptionInterface
     */
    private function download(string $url, string $name, \Closure $progress = null): void
    {
        $file = new \SplFileObject(PROJECT_ROOT . $name, 'wb+');

        foreach ($this->fetchContent($url, $progress) as $chunk) {
            $file->fwrite($chunk);
        }
    }

    /**
     * @param string $url
     * @param \Closure|null $progress
     * @return \Traversable
     * @throws TransportExceptionInterface
     */
    private function fetchContent(string $url, \Closure $progress = null): \Traversable
    {
        $response = $this->client->request(
            'GET',
            $url,
            [
                'on_progress' => $progress,
            ]
        );

        foreach ($this->client->stream($response) as $chunk) {
            yield $chunk->getContent();
        }
    }
}
