<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Traits;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ServerRequestInterface;

trait InteractWithServerRequest
{
    protected static function createDefaultServerRequest(): ServerRequestInterface
    {
        $psr17Factory = new Psr17Factory();

        $creator = new ServerRequestCreator(
            serverRequestFactory: $psr17Factory,
            uriFactory: $psr17Factory,
            uploadedFileFactory: $psr17Factory,
            streamFactory: $psr17Factory,
        );

        return $creator->fromGlobals();
    }
}
