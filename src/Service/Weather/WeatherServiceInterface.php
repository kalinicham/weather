<?php

namespace App\Service\Weather;

interface WeatherServiceInterface
{
    /**
     * Retrieve data from the weather API point
     *
     * @param string $city
     * @return array
     */
    public function execute(string $city): array;
}
