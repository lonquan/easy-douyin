<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\SimpleCache\InvalidArgumentException;
use EasyDouyin\Kernel\Traits\InteractWithHttpClient;
use EasyDouyin\Kernel\Contracts\Config as ConfigInterface;
use EasyDouyin\Kernel\Contracts\ClientToken as ClientTokenInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

abstract class ClientToken implements ClientTokenInterface
{
    const CACHE_KEY_PREFIX = 'client_token';

    use InteractWithHttpClient;

    public function __construct(
        protected ConfigInterface  $config,
        protected ?CacheInterface  $cache = null,
        protected ?LoggerInterface $logger = null
    )
    {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @return string
     */
    public function getAccessToken(): string
    {
        $token = $this->tryGetTokenFromCache();

        if ($token && is_string($token)) {
            return $token;
        }

        return $this->refresh();
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function tryGetTokenFromCache(): ?string
    {
        return $this->cache->get($this->getCacheKey());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws InvalidArgumentException
     * @return string
     */
    protected function refresh(): string
    {
        $this->http = $this->createHttp($this->config->get('http'));

        $response = $this->request(
            method: 'POST',
            uri: '/oauth/client_token/',
            options: [
                'json' => [
                    'client_key' => $this->config->getAppID(),
                    'client_secret' => $this->config->getAppSecret(),
                    'grant_type' => 'client_credential',
                ],
            ],
            useToken: false,
        );

        $accessToken = $response['data']['access_token'] ?? '';

        if (empty($accessToken)) {
            throw new HttpException('Failed to get access_token: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }

        $this->cache->set($this->getCacheKey(), $accessToken, intval($response['data']['expires_in']));

        return $accessToken;
    }

    protected function getCacheKey(): string
    {
        return sprintf(
            '%s.%s.%s',
            static::CACHE_KEY_PREFIX,
            $this->config->getAppID(),
            $this->config->getAppSecret(),
        );
    }
}
