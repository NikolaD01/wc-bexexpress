<?php

namespace WC_BE\Http\Template;

use WC_BE\Core\Factories\RepositoryFactory;

class BexCheckout
{
    public function __construct() {
        add_filter('woocommerce_states', [$this, 'states']);
    }

    public function states() : array
    {
        $municipalitiesRepository = RepositoryFactory::create('municipalities');
        $municipalities = $municipalitiesRepository->getAll();

        $countryCode = 'RS';
        $states[$countryCode] = [];

        foreach ($municipalities as $municipality) {
            if (isset($municipality->id, $municipality->name)) {
                $states[$countryCode][$municipality->id] = $municipality->name;
            }
        }


        return $states;
    }
}