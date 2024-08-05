<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife\Contracts;

use EasyDouyin\Kernel\Contracts\ClientToken as ClientTokenInterface;

interface Client
{
    public function getClientToken(): ClientTokenInterface;
}
