<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife;

use EasyDouyin\Kernel\Cache;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ServerRequestInterface;
use EasyDouyin\Kernel\Traits\InteractWithCache;
use EasyDouyin\Kernel\Traits\InteractWithLogger;
use EasyDouyin\Kernel\Traits\InteractWithServerRequest;
use EasyDouyin\Kernel\Contracts\Client as ClientInterface;
use EasyDouyin\Kernel\Contracts\Server as ServerInterface;
use Illuminate\Contracts\Container\BindingResolutionException;
use EasyDouyin\LocalLife\Contracts\Application as ApplicationInterface;

/**
 * 生活服务商家应用
 * @see https://partner.open-douyin.com/docs/resource/zh-CN/local-life/introduction/usage-guide
 */
class Application extends \EasyDouyin\Kernel\Application implements ApplicationInterface
{
    use InteractWithServerRequest;
    use InteractWithCache;
    use InteractWithLogger;

    /**
     * @throws BindingResolutionException
     */
    public function getClient(): Client
    {
        return $this->app->make(ClientInterface::class);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getServer(): Server
    {
        return $this->app->make(ServerInterface::class);
    }


    protected function booting(): void
    {
        $this->services = [
            CacheInterface::class => fn() => static::createFilesystemCache($this->getConfig()->getRuntimePath()),
            LoggerInterface::class => fn() => static::createFilesystemLogger($this->getConfig()->getRuntimePath()),
            ServerRequestInterface::class => fn() => static::createDefaultServerRequest(),
            ClientInterface::class => Client::class,
            ServerInterface::class => Server::class,
            EncryptorInterface::class => Encryptor::class,
        ];
    }
}
