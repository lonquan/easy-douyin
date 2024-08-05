<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ServerResponse implements ResponseInterface
{
    public function __construct(protected ResponseInterface $response)
    {
        $this->withHeader('Content-Type', 'application/json;charset=UTF-8');
        $this->rewindBody();
    }

    #[Pure]
    public static function make(ResponseInterface $response): ServerResponse
    {
        if ($response instanceof ServerResponse) {
            return $response;
        }

        return new self($response);
    }

    public function withResponse(ResponseInterface $response): static
    {
        $this->response = $response;

        return $this;
    }

    public function getProtocolVersion(): string
    {
        return $this->response->getProtocolVersion();
    }

    public function withProtocolVersion($version): static
    {
        $this->response = $this->response->withProtocolVersion($version);
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }

    public function hasHeader($name): bool
    {
        return $this->response->hasHeader($name);
    }

    public function getHeader($name): array
    {
        return $this->response->getHeader($name);
    }

    public function getHeaderLine($name): string
    {
        return $this->response->getHeaderLine($name);
    }

    public function withHeader($name, $value): static
    {
        return $this->withResponse($this->response->withHeader($name, $value));
    }

    public function withAddedHeader($name, $value): static
    {
        return $this->withResponse($this->response->withAddedHeader($name, $value));
    }

    public function withoutHeader($name): ServerResponse|ResponseInterface
    {
        return $this->withResponse($this->response->withoutHeader($name));
    }

    public function getBody(): StreamInterface
    {
        return $this->response->getBody();
    }

    public function withBody(StreamInterface $body): static
    {
        return $this->withResponse($this->response->withBody($body));
    }

    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function withStatus(int $code, string $reasonPhrase = ''): static
    {
        return $this->withResponse($this->response->withStatus($code, $reasonPhrase));
    }

    public function getReasonPhrase(): string
    {
        return $this->response->getReasonPhrase();
    }

    /**
     * @link https://github.com/symfony/http-foundation/blob/6.1/Response.php
     */
    public function send(): static
    {
        $this->sendHeaders();
        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } elseif (function_exists('litespeed_finish_request')) {
            litespeed_finish_request();
        } elseif (!in_array(PHP_SAPI, ['cli', 'phpdoc'], true)) {
            static::closeOutputBuffers(0, true);
        }

        return $this;
    }

    public function sendHeaders(): static
    {
        // headers have already been sent by the developer
        if (headers_sent()) {
            return $this;
        }

        foreach ($this->getHeaders() as $name => $values) {
            $replace = strcasecmp($name, 'Content-Type') === 0;

            foreach ($values as $value) {
                header($name . ': ' . $value, $replace, $this->getStatusCode());
            }
        }

        header(
            header: sprintf(
                'HTTP/%s %s %s',
                $this->getProtocolVersion(),
                $this->getStatusCode(),
                $this->getReasonPhrase(),
            ),
            response_code: $this->getStatusCode(),
        );

        return $this;
    }

    public function sendContent(): static
    {
        echo $this->getBody();

        return $this;
    }

    /**
     * Cleans or flushes output buffers up to target level.
     * Resulting level can be greater than target level if a non-removable buffer has been encountered.
     * @link https://github.com/symfony/http-foundation/blob/6.1/Response.php
     * @final
     */
    public static function closeOutputBuffers(int $targetLevel, bool $flush): void
    {
        $status = ob_get_status(true);
        $level = count($status);
        $flags = PHP_OUTPUT_HANDLER_REMOVABLE | ($flush ? PHP_OUTPUT_HANDLER_FLUSHABLE : PHP_OUTPUT_HANDLER_CLEANABLE);

        /** @noinspection PhpAssignmentInConditionInspection */
        while ($level-- > $targetLevel && ($s = $status[$level]) && (!isset($s['del']) ? !isset($s['flags']) || ($s['flags'] & $flags) === $flags : $s['del'])) {
            if ($flush) {
                ob_end_flush();
            } else {
                ob_end_clean();
            }
        }
    }

    public function __toString(): string
    {
        $headers = $this->getHeaders();
        $headersString = '';

        if (!empty($headers)) {
            ksort($headers);

            $max = max(array_map('strlen', array_keys($headers))) + 1;

            foreach ($headers as $name => $values) {
                $name = ucwords($name, '-');
                foreach ($values as $value) {
                    $headersString .= sprintf("%-{$max}s %s\r\n", $name . ':', $value);
                }
            }
        }

        return sprintf(
                'HTTP/%s %s %s',
                $this->getProtocolVersion(),
                $this->getStatusCode(),
                $this->getReasonPhrase(),
            ) . "\r\n" .
            $headersString . "\r\n" .
            $this->getBody();
    }

    protected function rewindBody(): void
    {
        $this->response->getBody()->rewind();
    }
}
