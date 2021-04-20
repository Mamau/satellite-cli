<?php
declare(strict_types=1);

namespace Mamau\Wkit\Entities;

/**
 * Class Asset
 * @package Mamau\Wkit\Entities
 */
final class Asset
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $uri;

    /**
     * @param  string  $name
     * @param  string  $uri
     */
    public function __construct(string $name, string $uri)
    {
        $this->name = $name;
        $this->uri = $uri;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
