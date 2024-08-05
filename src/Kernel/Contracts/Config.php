<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Contracts;

interface Config
{
    /**
     * @return array<string,mixed>
     */
    public function all(): array;

    public function has(string $key): bool;

    public function set(string $key, mixed $value = null): void;

    /**
     * @param array<string>|string $key
     */
    public function get(array|string $key, mixed $default = null): mixed;

    public function getAppID(): string;

    public function getAppSecret(): string;

    public function getRuntimePath(): string;

    public function enableDebug(): bool;
}
