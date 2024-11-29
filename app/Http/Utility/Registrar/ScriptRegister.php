<?php

namespace WC_BE\Http\Utility\Registrar;

class ScriptRegister
{
    protected static string $handle;
    protected static string $path;

    public static function enqueue(string $handle, string $path, bool $admin = false) : void
    {
        $hook = $admin ? 'admin_enqueue_scripts' : 'wp_enqueue_scripts';

        add_action($hook, function () use ($handle, $path) {
            self::register($handle, $path);
        }, 9999);
    }
    public static function register(string $handle, string $path): void
    {
        wp_enqueue_script(
            $handle,
            plugins_url($path, dirname(__DIR__, 3)),
            [],
            false,
            true
        );

        add_filter('script_loader_tag', function ($tag, $registered_handle) use ($handle) {
            if ($registered_handle === $handle) {
                return str_replace('src=', 'type="module" src=', $tag);
            }
            return $tag;
        }, 10, 2);

        wp_localize_script(
            $handle,
            'admin_globals',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'home_url' => home_url(),
            ]
        );
    }
}