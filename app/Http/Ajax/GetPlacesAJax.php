<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Core\Repositories\PlacesRepository;

class GetPlacesAJax implements AjaxInterface
{
    public function handle() : void
    {
        if (!isset($_POST['query'])) {
            wp_send_json_error('No query parameter provided.');
            return;
        }

        $query = sanitize_text_field($_POST['query']);

        $placesRepository = new PlacesRepository();

        $where = ['name LIKE' => '%' . $query . '%'];

        $places = $placesRepository->getAllWhere($where, ['id', 'name']);

        if ($places) {
            wp_send_json_success($places);
        } else {
            wp_send_json_error('No places found.');
        }
    }
}