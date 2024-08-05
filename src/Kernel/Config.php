<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use ArrayAccess;
use EasyDouyin\Kernel\Traits\HasAttributes;
use EasyDouyin\Kernel\Contracts\Config as ConfigInterface;
use EasyDouyin\Kernel\Exceptions\InvalidArgumentException;

abstract class Config implements ArrayAccess, ConfigInterface
{
    use HasAttributes;

    /**
     * @var array<string>
     */
    protected array $requiredKeys = [];

    /**
     * @param array<string,mixed> $items
     * @throws InvalidArgumentException
     */
    public function __construct(protected array $attributes)
    {
        $this->checkMissingKeys();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function checkMissingKeys(): bool
    {
        if (empty($this->requiredKeys)) {
            return true;
        }

        $missingKeys = [];

        foreach ($this->requiredKeys as $key) {
            if (!$this->has($key)) {
                $missingKeys[] = $key;
            }
        }

        if (!empty($missingKeys)) {
            throw new InvalidArgumentException(sprintf("\"%s\" cannot be empty.\r\n", implode(',', $missingKeys)));
        }

        return true;
    }

    public function getAppID(): string
    {
        return $this->get('app_id');
    }

    public function getAppSecret(): string
    {
        return $this->get('app_secret');
    }

    public function getRuntimePath(): string
    {
        return $this->get('runtime.path', sys_get_temp_dir());
    }

    public function enableDebug(): bool
    {
        return $this->get('runtime.debug', false);
    }

}
