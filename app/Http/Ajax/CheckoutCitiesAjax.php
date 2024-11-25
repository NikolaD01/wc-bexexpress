<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Core\Factories\RepositoryFactory;

class CheckoutCitiesAjax implements AjaxInterface
{
    public function handle() : void
    {
        if ( ! function_exists( 'WC' ) ) {
            wp_send_json_error( array( 'message' => 'WooCommerce not loaded.' ) );
        }

        $municipalityName = sanitize_text_field($_POST['municipalities_name']);

        $municipalitiesRepository = RepositoryFactory::create('municipalities');
        $municipality = $municipalitiesRepository->getOne(['name' => $municipalityName]);

        $placesRepository = RepositoryFactory::create('places');
        $cities = $placesRepository->getAllWhere(['municipalities_id' => $municipality->id], ['id', 'name']);

        wp_send_json_success($cities);
    }
}