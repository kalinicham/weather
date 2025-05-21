<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractApiService
{
    protected HttpClientInterface $httpClient;
    protected LoggerInterface $logger;

    /**
     * @param HttpClientInterface $httpClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient;
        $this->logger = $logger;
    }

    /**
     * @param array $params
     * @return string
     */
    abstract protected function buildUrl(array $params): string;

    /**
     * @param array $data
     * @return array
     */
    abstract protected function mapData(array $data): array;

    /**
     * @param mixed $params
     * @return array
     */
    protected function retrieveData(mixed $params): array
    {
        try {
            $url = $this->prepareUrl($params);
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();

            return $this->mapData($data);
        } catch (\Throwable $e) {
            $this->logger->error('API request error: ' . $e->getMessage());

            return ['error' => 'Failed to retrieve data.'];
        }
    }

    /**
     * @param mixed $params
     * @return array
     */
    protected function resolveParams(mixed $params): array
    {
        return match (true) {
            is_string($params) => [$params],
            is_array($params) => $params,
            default =>  throw new \InvalidArgumentException(
                'Unsupported param type. Expected string or array.'
            )
        };
    }

    /**
     * @param mixed $params
     * @return string
     */
    private function prepareUrl(mixed $params): string
    {
        $parsedParams = $this->resolveParams($params);

        return $this->buildUrl($parsedParams);
    }
}
