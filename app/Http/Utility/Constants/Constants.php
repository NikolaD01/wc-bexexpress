<?php

namespace WC_BE\Http\Utility\Constants;
class Constants
{
    const POST_TYPE = "bexexpress";
    const PREFIX_LOWER = "wc_be_";



    public static function getToken() : string
    {
        return self::getPrefix() . "token";
    }

    // ---------- DEFAULTS ----------
    public static function getPostType() : string
    {
        return self::POST_TYPE;
    }

    public static function getPrefix() : string
    {
        return self::PREFIX_LOWER . self::POST_TYPE . "_";
    }
    public static function getPrefixLower() : string
    {
        return self::PREFIX_LOWER;
    }




}