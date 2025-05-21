<?php

namespace App\Controller\Api;

use App\Service\Weather\WeatherServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class WeatherController extends AbstractController
{
    /**
     * @param WeatherServiceInterface $weatherService
     */
    public function __construct(private readonly WeatherServiceInterface $weatherService)
    {
    }

    /**
     * @param string $city
     * @return JsonResponse
     */
    public function getWeather(string $city): JsonResponse
    {
        $data = $this->weatherService->execute($city);

        return $this->json([
            'success' => !isset($data['error']),
            'result' => $data,
        ]);
    }
}
