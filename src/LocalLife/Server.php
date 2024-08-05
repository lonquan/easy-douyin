<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife;

use Nyholm\Psr7\Response;
use EasyDouyin\Kernel\Utils;
use EasyDouyin\Kernel\ServerResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use EasyDouyin\Kernel\Traits\InteractWithHandlers;
use EasyDouyin\Kernel\Exceptions\BadRequestException;
use EasyDouyin\Kernel\Contracts\Server as ServerInterface;
use EasyDouyin\Kernel\Exceptions\InvalidArgumentException;
use EasyDouyin\Kernel\Contracts\Template as TemplateInterface;

class Server implements ServerInterface
{
    use InteractWithHandlers;

    public function __construct(
        protected ServerRequestInterface $request,
        protected Encryptor              $encryptor
    )
    {
    }

    /**
     * @throws InvalidArgumentException
     * @throws BadRequestException
     * @return ResponseInterface
     */
    public function serve(): ResponseInterface
    {
        $this->prependHandler(function (Message $message, callable $next) {
            if ($message->getPushType() === Message::$webhookType) {
                $this->encryptor->verifyWebHookSignature($message);

                if ($message->isEvent('verify_webhook')) {
                    return $message->getContent(false);
                }
            }

            if ($message->getPushType() === Message::$sipType) {
                $this->encryptor->verifySipSignature($message);
            }

            return $next($message);
        });

        $response = $this->handle(new Response(200), $this->getRequestMessage());

        if (!($response instanceof ResponseInterface)) {
            $response = $this->transformToResponse($response);
        }

        return ServerResponse::make($response);
    }

    /**
     * @param ServerRequestInterface|null $request
     * @throws BadRequestException
     * @return Message
     */
    public function getRequestMessage(?ServerRequestInterface $request = null): Message
    {
        return Message::createFromRequest($request ?: $this->request);
    }

    protected function transformToResponse(mixed $response): ResponseInterface
    {
        $response = match (true) {
            $response instanceof TemplateInterface => (string)$response,
            is_array($response) => json_encode($response, JSON_ERROR_NONE),
            default => $response,
        };

        return new Response(status: 200, body: $response);
    }
}
