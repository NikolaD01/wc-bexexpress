<?php

namespace WC_BE\Http\Utility\Registrar;

class ScriptRegister
{
    protected string $handle;
    protected string $path;


    public function enqueue(string $handle, string $path) : void
    {
        $this->handle = $handle;
        $this->path = $path;

        add_action('admin_enqueue_scripts', [$this, 'register']);
    }
    public function register() : void {
        wp_enqueue_script(
            $this->handle,
            plugins_url($this->path, dirname(__DIR__, 3)),
            [],
            false,
            true
        );
        wp_localize_script(
            $this->handle,
            'admin_globals',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'home_url' => home_url(),
            )
        );
    }
}