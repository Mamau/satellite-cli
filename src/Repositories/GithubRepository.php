<?php
declare(strict_types=1);

namespace Mamau\Wkit\Repositories;

use Mamau\Wkit\Entities\Asset;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class GithubRepository
 * @package Mamau\Wkit\Repositories
 */
final class GithubRepository
{
    const GITHUB_API_URI = 'https://api.github.com/repos/Mamau/starter/releases';

    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * GithubRepository constructor.
     */
    public function __construct()
    {
        $this->client = HttpClient::create();
    }

    /**
     * @return Asset[]
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchReleases(): array
    {
        $assets = [];
        $response = $this->client->request('GET', self::GITHUB_API_URI);
        foreach ($response->toArray() as $release) {
            foreach ($release['assets'] as $asset) {
                $assets[] = new Asset($asset['name'], $asset['browser_download_url']);
            }
        }

        return $assets;
    }

    /**
     * @param  string  $url
     * @param  \Closure|null  $progress
     * @return \Traversable
     * @throws TransportExceptionInterface
     */
    public function downloadBinary(string $url, \Closure $progress = null): \Traversable
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
