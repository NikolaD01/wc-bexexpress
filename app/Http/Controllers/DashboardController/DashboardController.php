<?php

namespace WC_BE\Http\Controllers\DashboardController;

use WC_BE\Dependencies\Twig\Error\RuntimeError;
use WC_BE\Dependencies\Twig\Error\SyntaxError;
use WC_BE\Dependencies\Twig\Error\LoaderError;
use WC_BE\Http\Utility\Constants\Constants;
use WC_BE\Http\Utility\Helpers\OptionsHelper;
use WC_BE\Http\Utility\Helpers\View;

class DashboardController extends AbstractOptionsController
{
    public function __construct(

    )
    {
        $this->pageTitle = 'BexExpress Settings';
        $this->menuTitle = 'BexExpress';
        $this->capability = 'manage_options';
        $this->menuSlug = Constants::getPostType();

        parent::__construct();
    }

    public function processForm(): void
    {
        $statement = (isset($_POST['wc_be_save_api_token']) && check_admin_referer('wc_be_save_api_token', 'wc_be_save_api_token'));
        $this->handle( $statement, function() {
            isset($_POST['wc-be-api-token']) ? OptionsHelper::storeToken($_POST['wc-be-api-token']) : OptionsHelper::storeToken(null);
        });

    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function view() : void
    {

        $nonceField = wp_nonce_field(Constants::PREFIX_LOWER.'save_api_token', Constants::PREFIX_LOWER.'save_api_token', true, false);

        View::render('dashboard.twig', [
            'title' => $this->pageTitle,
            'nonce_field' => $nonceField,
        ]);
    }
}