<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Core\Repositories\StreetsRepository;

class GetStreetsAjax implements AjaxInterface
{
    public function handle () : void
    {
        if (!isset($_POST['query'])) {
            wp_send_json_error('No query parameter provided.');
            return;
        }

        $query = sanitize_text_field($_POST['query']);

        $streetsRepository = new StreetsRepository();

        $where = ['name LIKE' => '%' . $query . '%'];

        $streets = $streetsRepository->getAllWhere($where, ['id', 'name']);

        if ($streets) {
            wp_send_json_success($streets);
        } else {
            wp_send_json_error('No streets found.');
        }
    }
}