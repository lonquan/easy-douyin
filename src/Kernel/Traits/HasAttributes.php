<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Traits;

use Illuminate\Support\Arr;

trait HasAttributes
{
    /**
     * @param array<int|string,mixed> $attributes
     */
    public function __construct(protected array $attributes)
    {
    }

    /**
     * @return array<int|string,mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function __toString()
    {
        return $this->toJson() ?: '';
    }

    public function toJson(int $flag = JSON_ERROR_NONE): string|false
    {
        return json_encode($this->attributes, $flag);
    }

    /**
     * @return array<int|string,mixed> $attributes
     */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    public function has(string $key): bool
    {
        return Arr::has($this->attributes, $key);
    }

    /**
     * @param array<string>|string $key
     */
    #[Pure]
    public function get(array|string $key, mixed $default = null): mixed
    {
        if (is_array($key)) {
            return $this->getMany($key);
        }

        return Arr::get($this->attributes, $key, $default);
    }

    /**
     * @param array<string> $keys
     * @return array<string, mixed>
     */
    #[Pure]
    public function getMany(array $keys): array
    {
        $config = [];

        foreach ($keys as $key => $default) {
            if (is_numeric($key)) {
                [$key, $default] = [$default, null];
            }

            $config[$key] = Arr::get($this->attributes, $key, $default);
        }

        return $config;
    }

    public function set(string $key, mixed $value = null): void
    {
        Arr::set($this->attributes, $key, $value);
    }

    /**
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->attributes;
    }

    #[Pure]
    public function offsetExists(mixed $key): bool
    {
        return $this->has(strval($key));
    }

    #[Pure]
    public function offsetGet(mixed $key): mixed
    {
        return $this->get(strval($key));
    }

    public function offsetSet(mixed $key, mixed $value): void
    {
        $this->set(strval($key), $value);
    }

    public function offsetUnset(mixed $key): void
    {
        $this->set(strval($key));
    }
}
