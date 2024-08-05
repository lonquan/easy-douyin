<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Contracts;

interface Application
{
    public function getConfig(): Config;
}
