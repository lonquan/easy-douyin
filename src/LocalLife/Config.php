<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife;

class Config extends \EasyDouyin\Kernel\Config
{
    protected array $requiredKeys = [
        'app_id',
        'app_secret',
    ];
}
