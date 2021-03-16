<?php

declare(strict_types=1);

namespace Mamau\Wkit\Environment;

/**
 * Class OperatingSystem
 * @package Mamau\Wkit\Environment
 */
final class OperatingSystem
{
    /**
     * @var string
     */
    public const OS_DARWIN = 'darwin';

    /**
     * @var string
     */
    public const OS_LINUX = 'linux';

    /**
     * @var string
     */
    public const OS_WINDOWS = 'windows';

    /**
     * @return string
     */
    public static function getCurrentOSName(): string
    {
        switch (\PHP_OS_FAMILY) {
            case 'Windows':
                return self::OS_WINDOWS;

            case 'Darwin':
                return self::OS_DARWIN;

            case 'Linux':
                return self::OS_LINUX;

            default:
                throw new \OutOfRangeException(\sprintf('Current OS (%s) may not be supported', \PHP_OS_FAMILY));
        }
    }

}
