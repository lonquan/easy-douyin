<?php
declare(strict_types=1);

namespace EasyDouyin\Kernel\Traits;

use GuzzleHttp\Utils;
use GuzzleHttp\Client;
use GuzzleHttp\Middleware;
use GuzzleHttp\ClientTrait;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client as Http;
use Composer\InstalledVersions;
use GuzzleHttp\Promise\PromiseInterface;
use Symfony\Component\HttpClient\HttpClient;
use Psr\SimpleCache\InvalidArgumentException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;

trait InteractWithHttpClient
{
    protected HttpClientInterface $http;

    /**
     * @param string $uri
     * @param array $options
     * @param bool $appToken
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @return array
     */
    public function get(string $uri, array $options = [], bool $useToken = true): array
    {
        return $this->request('GET', $uri, $options);
    }

    /**
     * @param string $uri
     * @param array $options
     * @param bool $appToken
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @return array
     */
    public function post(string $uri, array $options = [], bool $useToken = true): array
    {
        return $this->request('POST', $uri, $options);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $options
     * @param bool $token
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @return array
     */
    public function request(string $method, string $uri, array $options = [], bool $useToken = true): array
    {
        if ($useToken && method_exists($this, 'getClientToken')) {
            $options['headers']['Access-Token'] = $this->getClientToken()->getAccessToken();
        }

        $response = $this->http->request($method, $uri, $options);

        $this->logger?->debug("HttpClient {$method} {$uri}}", [
            'status' => $response->getStatusCode(),
            'option' => $options,
            'headers' => $response->getHeaders(),
            'body' => $response->toArray(),
        ]);

        return $response->toArray();
    }

    protected function createHttp(array $options = []): HttpClientInterface
    {
        $defaultOptions = [
            'base_uri' => 'https://open.douyin.com',
            'timeout' => 5.0,
            'headers' => ['Content-Type' => 'application/json'],
        ];

        $defaultOptions = $options + $defaultOptions;

        if (class_exists(InstalledVersions::class)) {
            $defaultOptions['headers']['User-Agent'] = (string)InstalledVersions::getVersion('antcool/easy-douyin');
        }

        return HttpClient::create($defaultOptions);
    }
}
