<?php
namespace WC_BE\Http\Utility\Helpers;



use WC_BE\Dependencies\Twig\Environment;
use WC_BE\Dependencies\Twig\Error\LoaderError;
use WC_BE\Dependencies\Twig\Error\RuntimeError;
use WC_BE\Dependencies\Twig\Error\SyntaxError;
use WC_BE\Dependencies\Twig\Loader\FilesystemLoader;

class View
{
    protected static ?Environment $twig = null;

    protected static function initialize(): void
    {
        if (self::$twig === null) {
            $loader = new FilesystemLoader(plugin_dir_path(dirname(__DIR__, 2)) . 'resources/views/');
            self::$twig = new Environment($loader);
        }
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public static function render(string $view, array $args = []): void
    {
        self::initialize();
        echo self::$twig->render($view, $args);
    }
}
