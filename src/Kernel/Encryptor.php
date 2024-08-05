<?php

declare(strict_types=1);

namespace EasyDouyin\Kernel;

use EasyDouyin\Kernel\Contracts\Config as ConfigInterface;
use EasyDouyin\Kernel\Contracts\Encryptor as EncryptorInterface;

abstract class Encryptor implements EncryptorInterface
{
    public function __construct(protected ConfigInterface $config)
    {
    }
}
