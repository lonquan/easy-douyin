<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use EasyDouyin\Kernel\Contracts\Jsonable;
use EasyDouyin\Kernel\Traits\HasAttributes;
use EasyDouyin\Kernel\Contracts\Template as TemplateInterface;

abstract class Template implements TemplateInterface, Jsonable
{
    use HasAttributes;

    public function toArray(): array
    {
        return ['data' => $this->attributes,];
    }

    public function toJson(int $flag = JSON_ERROR_NONE): string|false
    {
        return json_encode($this->toArray(), $flag);
    }

    public function __toString(): string
    {
        return $this->toJson() ?: '';
    }
}
