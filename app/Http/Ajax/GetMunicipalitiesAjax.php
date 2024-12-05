<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Core\Repositories\MunicipalitiesRepository;

class GetMunicipalitiesAjax implements AjaxInterface
{
    public function handle() : void
    {
        if (!isset($_POST['query'])) {
            wp_send_json_error('No query parameter provided.');
            return;
        }

        $query = sanitize_text_field($_POST['query']);

        $municipalitiesRepository = new MunicipalitiesRepository();

        $where = ['name LIKE' => '%' . $query . '%'];

        $municipalities = $municipalitiesRepository->getAllWhere($where, ['id', 'name']);

        if ($municipalities) {
            wp_send_json_success($municipalities);
        } else {
            wp_send_json_error('No municipalities found.');
        }
    }
}