<?php
declare(strict_types=1);

namespace Mamau\Wkit\Services;

use Mamau\Wkit\Repositories\GithubRepository;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Class GithubService
 * @package Mamau\Wkit\Services
 */
final class GithubService
{
    public const OS_DARWIN = 0;
    public const OS_LINUX = 1;
    public const OS_WINDOWS = 2;

    public const OS_LIST = [
        self::OS_DARWIN => 'Darwin',
        self::OS_LINUX => 'Linux',
        self::OS_WINDOWS => 'Windows',
    ];

    /**
     * @var GithubRepository
     */
    private $repository;

    /**
     * GithubService constructor.
     */
    public function __construct()
    {
        $this->repository = new GithubRepository();
    }

    /**
     * @param  int  $idOs
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchBinary(int $idOs): void
    {
        $assets = $this->repository->fetchReleases();

        foreach ($assets as $asset) {
            if (str_contains($asset->getName(), strtolower(self::OS_LIST[$idOs]))) {
                $file = new \SplFileObject(__DIR__. '/../../' . $asset->getName(), 'wb+');

                foreach ($this->repository->downloadBinary($asset->getUri())as $chunk) {
                    $file->fwrite($chunk);
                }
            }
        }
    }
}
