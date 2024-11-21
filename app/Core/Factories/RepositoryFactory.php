<?php

namespace WC_BE\Core\Factories;



use WC_BE\Core\Contracts\RepositoryInterface;
use WC_BE\Core\Repositories\MunicipalitiesRepository;
use WC_BE\Core\Repositories\PlacesRepository;
use WC_BE\Core\Repositories\StreetsRepository;

class RepositoryFactory
{
    public static function create($type): ?RepositoryInterface
    {
        return match ($type) {
            'municipalities' => new MunicipalitiesRepository(),
            'places' => new PlacesRepository(),
            'streets' => new StreetsRepository(),
            default => null
        };
    }
}