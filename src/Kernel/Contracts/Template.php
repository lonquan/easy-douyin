<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Contracts;

interface Template
{
    public function toArray(): array;

    public function toJson(): string|false;

    public function __toString(): string;
}
