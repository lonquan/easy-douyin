<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Contracts;

interface Arrayable
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(): array;
}
