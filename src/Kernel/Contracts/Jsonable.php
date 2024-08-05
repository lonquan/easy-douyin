<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Contracts;

interface Jsonable
{
    /**
     * @return string
     */
    public function toJson(): string|false;
}
