<?php

namespace WC_BE\Http\Utility\Helpers;

use WC_BE\Http\Utility\Constants\Constants;

class OptionsHelper
{
    public static function storeToken(string $token, ?int $expiry_time = null): void
    {
        $existing_tokens_json = get_option(Constants::getToken(), json_encode([]));
        $existing_tokens = json_decode($existing_tokens_json, true);

        $existing_tokens[$token] = [
            'expires' => $expiry_time,
        ];

        update_option(Constants::getToken(), json_encode($existing_tokens));
    }

    public static function validateToken(string $token): bool
    {
        $existing_tokens_json = get_option(Constants::getToken(), json_encode([]));
        $existing_tokens = json_decode($existing_tokens_json, true);

        if (isset($existing_tokens[$token])) {
            $data = $existing_tokens[$token];

            if ($data['expires'] === null || $data['expires'] > time()) {
                return true;
            }
        }
        return false;
    }
}