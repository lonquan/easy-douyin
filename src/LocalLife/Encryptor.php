<?php
declare(strict_types=1);

namespace EasyDouyin\LocalLife;

use EasyDouyin\Kernel\Exceptions\InvalidArgumentException;

class Encryptor extends \EasyDouyin\Kernel\Encryptor
{
    /**
     * @throws InvalidArgumentException
     */
    public function verifyWebHookSignature(Message $message): bool
    {
        $wholeStr = $this->config->getAppSecret() . $message->getOriginalContents();
        $sign = hash('sha1', $wholeStr);

        if ($sign !== $message->getDouyinSignature()) {
            throw new InvalidArgumentException('The webhook signature is invalid.');
        }

        return true;
    }

    /**
     * @param Message $message
     * @throws InvalidArgumentException
     * @return bool
     */
    public function verifySipSignature(Message $message): bool
    {
        $request = $message->getRequest();
        $params = $request->getQueryParams();
        unset($params['sign']);
        ksort($params);

        $wholeStr = sprintf('%s&%s', $this->config->getAppSecret(), http_build_query($params));
        $wholeStr .= $request->getMethod() === 'POST' ? '&http_body=' . $message->getOriginalContents() : '';

        $sign = hash('sha256', $wholeStr);

        if ($sign !== $request->getHeaderLine('X-Life-Sign')) {
            throw new InvalidArgumentException('The sip signature is invalid.');
        }

        return true;
    }
}
