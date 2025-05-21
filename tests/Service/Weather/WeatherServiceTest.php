<?php

namespace App\Tests\Service\Weather;

use App\Service\Weather\WeatherService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Yaml\Yaml;

class WeatherServiceTest extends TestCase
{
    private const CITY = 'Kyiv';
    private const API_KEY = 'test-api-key';
    private const API_URL = 'test-api-irl';

    public function testService(): void
    {
        $mockApiResponse = Yaml::parseFile(__DIR__ . '/data/fixture.yaml');
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock
            ->method('toArray')
            ->willReturn($mockApiResponse);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock
            ->method('request')
            ->willReturn($responseMock);

        $weatherService = new WeatherService(
            $httpClientMock,
            $this->createMock(LoggerInterface::class),
            self::API_KEY,
            self::API_URL
        );

        $result = $weatherService->execute(self::CITY);

        $expected = Yaml::parseFile(__DIR__ . '/data/expected.yaml');
        $this->assertEquals($expected, $result);
    }

    public function testExceptionService(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock
            ->method('request')
            ->willThrowException(new \Exception('TestException'));

        $weatherService = new WeatherService(
            $httpClientMock,
            $this->createMock(LoggerInterface::class),
            self::API_KEY,
            self::API_URL
        );
        $result = $weatherService->execute(self::CITY);
        $this->assertEquals(['error' => 'Failed to retrieve data.'], $result);
    }
}
