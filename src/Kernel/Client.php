<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use EasyDouyin\Kernel\Traits\InteractWithHttpClient;
use EasyDouyin\Kernel\Contracts\Client as ClientInterface;
use EasyDouyin\Kernel\Contracts\Config as ConfigInterface;

abstract class Client implements ClientInterface
{
    use InteractWithHttpClient;

    public function __construct(
        protected ConfigInterface  $config,
        protected ?CacheInterface  $cache = null,
        protected ?LoggerInterface $logger = null
    )
    {
        $this->http = $this->createHttp(options: $this->config->get('http', []));
    }
}
