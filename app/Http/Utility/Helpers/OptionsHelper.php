<?php

namespace WC_BE\Http\Utility\Helpers;

use WC_BE\Http\Utility\Constants\Constants;

class OptionsHelper
{
    public static function storeToken(string $token): void
    {
        if($token)
        update_option(Constants::getToken(), json_encode($token));
    }

}