<?php

namespace App\Controller;

use App\Service\Weather\WeatherServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class WeatherController extends AbstractController
{
    //Можна через атрибути
    //#[Route('/weather/{city}', name: 'weather', defaults: ['city' => 'Kyiv'])]
    /**
     * @param string $city
     * @param WeatherServiceInterface $weatherService
     * @return Response
     */
    public function index(string $city, WeatherServiceInterface $weatherService): Response
    {
        $weatherData = $weatherService->execute($city);

        return $this->render(
            'weather/index.html.twig',
            [
                'weatherData' => $weatherData,
                'title' => $city,
            ]
        );
    }
}
