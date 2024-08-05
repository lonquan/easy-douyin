<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use EasyDouyin\Kernel\Contracts\Jsonable;
use EasyDouyin\Kernel\Traits\HasAttributes;
use Psr\Http\Message\ServerRequestInterface;
use EasyDouyin\Kernel\Exceptions\BadRequestException;

abstract class Message implements \JsonSerializable, \ArrayAccess, Jsonable
{
    use HasAttributes;

    static string $webhookType = 'webhook';
    static string $sipType = 'sip';

    protected string $pushType;

    /**
     * @param array<string, mixed> $attributes
     * @param string $originContent
     * @param array{type:string, headers: array} $properties
     */
    public function __construct(
        protected array                   $attributes,
        protected string                  $originContent = '',
        protected ?ServerRequestInterface $request = null,
    )
    {
    }

    /**
     * @throws BadRequestException
     */
    public static function createFromRequest(ServerRequestInterface $request): static
    {
        $originContent = $request->getBody()->getContents();

        return new static(
            attributes: static::format($originContent),
            originContent: $originContent,
            request: $request,
        );
    }

    /**
     * @throws BadRequestException
     */
    public static function format(string $originContent): array
    {
        $dataSet = json_decode($originContent, true);

        if (json_last_error() === JSON_ERROR_NONE && $originContent) {
            $attributes = $dataSet;
        }

        if (empty($attributes) || !is_array($attributes)) {
            throw new BadRequestException('Failed to decode request contents.');
        }

        return $attributes;
    }

    /**
     * Retrieve the content from the body
     * @return array|null
     */
    public function getContent(bool $decode = true): array|string|null
    {
        $content = $this->get('content');

        if ($content && $decode) {
            $decodeContent = json_decode($content, true);
            $content = json_last_error() === 0 ? $decodeContent : $content;
        }

        return $content;
    }

    public function isEvent(string $name): bool
    {
        return $this->getEvent() === $name;
    }

    /**
     * Retrieve the event from the body
     * @return string|null
     */
    public function getEvent(): ?string
    {
        return $this->get('event');
    }

    /**
     * Retrieve the douyin signature from the headers
     * @return string|null
     */
    public function getDouyinSignature(): ?string
    {
        return $this->request->getHeaderLine('X-Douyin-Signature');
    }

    public function getMsgId(): ?string
    {
        return $this->request->getHeaderLine('Msg-Id');
    }

    public function getPushType(): ?string
    {
        if (empty($this->pushType)) {
            $this->pushType = match (true) {
                $this->request->hasHeader('X-Douyin-Signature') => static::$webhookType,
                $this->request->hasHeader('X-Bytedance-Logid') => static::$sipType,
                default => null
            };
        }

        return $this->pushType;
    }

    public function getOriginalContents(): string
    {
        return $this->originContent;
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }
}
