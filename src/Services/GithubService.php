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
                $file = new \SplFileObject(PROJECT_ROOT.$asset->getName(), 'wb+');

                foreach ($this->repository->downloadBinary($asset->getUri(), $progress) as $chunk) {
                    $file->fwrite($chunk);
                }
            }
        }
    }
}
