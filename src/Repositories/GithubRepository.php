<?php
declare(strict_types=1);

namespace Mamau\Wkit\Repositories;

use Mamau\Wkit\Entities\Asset;
use Mamau\Wkit\Entities\FileContent;
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
    const GITHUB_API_URI = 'https://api.github.com/repos/Mamau/starter/';

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

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
        $url = self::GITHUB_API_URI.'releases/latest';
        $response = $this->client->request('GET', $url);
        foreach ($response->toArray()['assets'] as $asset) {
            $assets[] = new Asset($asset['name'], $asset['browser_download_url']);
        }

        return $assets;
    }

    /**
     * @param  string  $fileName
     * @return FileContent
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchFileContent(string $fileName): FileContent
    {
        $url = sprintf('%scontents/%s', self::GITHUB_API_URI, $fileName);
        $response = $this->client->request('GET', $url);
        $data = $response->toArray();

        return new FileContent($data['name'], $data['download_url']);
    }
}
