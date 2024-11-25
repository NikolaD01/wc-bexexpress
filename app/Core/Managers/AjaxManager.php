<?php

namespace WC_BE\Core\Managers;

use WC_BE\Core\Factories\AjaxFactory;

class AjaxManager
{
    public function __construct()
    {
        add_action('wp_ajax_wc_bex_manager', [$this, 'handle']);
        add_action('wp_ajax_nopriv_wc_bex_manager', [$this, 'handle']);
    }

    public function handle(): void
    {
        $action_name = sanitize_text_field($_POST['action_name'] ?? '');

        $action = AjaxFactory::create($action_name);

        if ($action) {
            $action->handle();
        } else {
            wp_send_json_error('Invalid AJAX action.');
        }
    }
}