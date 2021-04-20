<?php
declare(strict_types=1);

namespace Mamau\Wkit\Entities;

/**
 * Class FileContent
 * @package Mamau\Wkit\Entities
 */
class FileContent
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $downloadUrl;

    /**
     * FileContent constructor.
     * @param  string  $name
     * @param  string  $url
     */
    public function __construct(string $name, string $url)
    {
        $this->name = $name;
        $this->downloadUrl = $url;
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
    public function getDownloadUrl(): string
    {
        return $this->downloadUrl;
    }
}
