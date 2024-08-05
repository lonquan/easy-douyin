<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife;

use EasyDouyin\LocalLife\Contracts\Client as ClientInterface;

class Client extends \EasyDouyin\Kernel\Client implements ClientInterface
{
    public function getClientToken(): ClientToken
    {
        return new ClientToken(config: $this->config, cache: $this->cache, logger: $this->logger);
    }
}
