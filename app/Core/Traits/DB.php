<?php

namespace WC_BE\Core\Traits;

use wpdb;

trait DB
{
    /**
     * Get the global wpdb instance.
     *
     * @return wpdb
     */
    protected function db(): wpdb
    {
        global $wpdb;
        return $wpdb;
    }
}