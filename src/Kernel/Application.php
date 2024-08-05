<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use Illuminate\Container\Container;
use EasyDouyin\Kernel\Contracts\Config as ConfigInterface;
use EasyDouyin\Kernel\Exceptions\InvalidArgumentException;
use Illuminate\Contracts\Container\BindingResolutionException;
use EasyDouyin\Kernel\Contracts\Application as ApplicationInterface;

abstract class Application implements ApplicationInterface
{
    protected Container $app;

    protected string $runtime;

    protected array $services = [];

    public function __construct(array|ConfigInterface $config)
    {
        $this->app = new Container();

        $this->app->singleton(
            ConfigInterface::class,
            fn(Container $app) => is_array($config) ? $this->getConfigFromArray($config) : $config,
        );

        $this->boot();
    }

    /**
     * @throws BindingResolutionException
     */
    public function getConfig(): ConfigInterface
    {
        return $this->app->make(ConfigInterface::class);
    }

    protected function boot(): void
    {
        method_exists($this, 'booting') && $this->booting();

        foreach ($this->services as $service => $concrete) {
            $this->app->bind($service, $concrete);
        }

        method_exists($this, 'booted') && $this->booted();
    }

    /**
     * @param array<string, mixed> $config
     * @throws InvalidArgumentException
     * @return ConfigInterface
     * @noinspection PhpDocRedundantThrowsInspection
     */
    protected function getConfigFromArray(array $config): ConfigInterface
    {
        $class = (new \ReflectionClass(static::class))->getNamespaceName() . '\Config';

        return new $class($config);
    }
}
