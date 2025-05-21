<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\Service\AbstractApiService;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService extends AbstractApiService implements WeatherServiceInterface
{
    private string $apiKey;
    private string $apiUrl;

    /**
     * @param HttpClientInterface $httpClient
     * @param LoggerInterface $logger
     * @param string $weatherApiKey
     * @param string $weatherApiUrl
     */
    public function __construct(
        HttpClientInterface $httpClient,
        LoggerInterface $logger,
        string $weatherApiKey,
        string $weatherApiUrl
    ) {
        parent::__construct($httpClient, $logger);
        $this->apiKey = $weatherApiKey;
        $this->apiUrl = $weatherApiUrl;
    }

    /**
     * @inheritDoc
     */
    protected function buildUrl(array $params): string
    {
        return sprintf(
            $this->apiUrl . '?key=%s&q=%s',
            $this->apiKey,
            urlencode($params[0])
        );
    }

    /**
     * @inheritDoc
     */
    protected function mapData(array $data): array
    {
        return [
            'city' => $data['location']['name'] ?? '',
            'country' => $data['location']['country'] ?? '',
            'temperature' => $data['current']['temp_c'] ?? '',
            'condition' => $data['current']['condition']['text'] ?? '',
            'humidity' => $data['current']['humidity'] ?? '',
            'wind_speed' => $data['current']['wind_kph'] ?? '',
            'last_updated' => $data['current']['last_updated'] ?? '',
        ];
    }

    /**
     * @inheritDoc
     */
    public function execute(string $city): array
    {
        return $this->retrieveData($city);
    }
}
