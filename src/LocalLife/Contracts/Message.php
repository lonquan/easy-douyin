<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife\Contracts;

interface Message
{
    /**
     * Request body content
     * @return string|null
     */
    public function getEvent(): ?string;

    /**
     * Request body content
     * @return string|null
     */
    public function getLogId(): ?string;

    /**
     * Request body content
     * @return string|null
     */
    public function getContent(): null|string|array;

    /**
     * Request body content
     * @return string|null
     */
    public function getEventId(): ?string;

    /**
     * Request body content
     * @return string|null
     */
    public function getFromUserId(): ?string;

    /**
     * Request headers content: msg-id
     * @return string|null
     */
    public function getMessageId(): ?string;

    /**
     * Request headers content: x-douyin-signature
     * @return string|null
     */
    public function getSignature(): ?string;
}
