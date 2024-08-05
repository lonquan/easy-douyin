<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Traits;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\RotatingFileHandler;

trait InteractWithLogger
{
    public static function createFilesystemLogger(string $path, int $days = 30): LoggerInterface
    {
        $logger = new Logger('EasyDouyin');

        $logger->pushHandler(new RotatingFileHandler($path . DIRECTORY_SEPARATOR . 'logs/easy-douyin.log', $days));

        return $logger;
    }
}
