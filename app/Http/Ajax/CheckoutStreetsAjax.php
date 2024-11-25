<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Core\Factories\RepositoryFactory;

class CheckoutStreetsAjax implements AjaxInterface
{
    public function handle() : void
    {
        if (! function_exists('WC')) {
            wp_send_json_error(array('message' => 'WooCommerce Not loaded.'));
        }

        $placeId = sanitize_text_field($_POST['place_id']);

        $streetsRepository = RepositoryFactory::create('streets');
        $streets = $streetsRepository->getAllWhere(['place_id' => $placeId]);

        wp_send_json_success($streets);
    }
}