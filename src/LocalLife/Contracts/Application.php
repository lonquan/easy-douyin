<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife\Contracts;

use EasyDouyin\Kernel\Contracts\Account as AccountInterface;
use EasyDouyin\Kernel\Contracts\Client as ClientInterface;
use EasyDouyin\Kernel\Contracts\Config as ConfigInterface;
use EasyDouyin\Kernel\Contracts\Server as ServerInterface;
use EasyDouyin\Kernel\Contracts\Encryptor as EncryptorInterface;

interface Application
{
    public function getClient(): ClientInterface;

    public function getServer(): ServerInterface;
}
