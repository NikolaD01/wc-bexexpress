<?php

namespace WC_BE\Core\Factories;



use WC_BE\Core\Contracts\SeederInterface;
use WC_BE\Core\Repositories\Seeders\MunicipalitiesSeeder;
use WC_BE\Core\Repositories\Seeders\PlacesSeeder;
use WC_BE\Core\Repositories\Seeders\StreetsSeeder;

class SeederFactory
{
    public static function create($type): ?SeederInterface
    {
        return match ($type) {
            'municipalities' => new MunicipalitiesSeeder(),
            'places' => new PlacesSeeder(),
            'streets' => new StreetsSeeder(),
            default => null
        };
    }
}