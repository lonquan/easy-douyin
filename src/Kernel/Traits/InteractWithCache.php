<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Traits;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

trait InteractWithCache
{
    public static function createFilesystemCache(string $path): CacheInterface
    {
        return new Psr16Cache(
            new FilesystemAdapter(
                namespace: 'easy-douyin',
                defaultLifetime: 1500,
                directory: $path . DIRECTORY_SEPARATOR . 'cache',
            ),
        );
    }
}
